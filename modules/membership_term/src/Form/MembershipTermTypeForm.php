<?php

namespace Drupal\membership_term\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\scheduled_message\Plugin\ScheduledMessageManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MembershipTermTypeForm.
 *
 * @package Drupal\membership_term\Form
 */
class MembershipTermTypeForm extends EntityForm {

  /**
   * @var \Drupal\membership_term\Entity\MembershipTermTypeInterface
   */
  protected $entity;

  protected $membershipTypeStorage;

  protected $scheduledMessageManager;

  public function __construct(EntityStorageInterface $membership_type_storage, ScheduledMessageManager $scheduledMessageManager) {
    $this->membershipTypeStorage = $membership_type_storage;
    $this->scheduledMessageManager = $scheduledMessageManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('membership_type'),
      $container->get('plugin.manager.scheduled_message')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $membership_type_configs = $this->membershipTypeStorage->loadMultiple();

    $membership_types = array();
    foreach ($membership_type_configs as $k => $type) {
      /** @var \Drupal\membership\Entity\MembershipTypeInterface $type */
      $membership_types[$k] = $type->label();
    }

    /** @var \Drupal\state_machine\WorkflowManagerInterface $workflow_manager */
    $workflow_manager = \Drupal::service('plugin.manager.workflow');
    $workflows = $workflow_manager->getGroupedLabels('membership_term');

    /** @var \Drupal\membership_term\Entity\MembershipTermTypeInterface $membership_term_type */
    $membership_term_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $membership_term_type->label(),
      '#description' => $this->t("Label for the Membership term type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $membership_term_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\membership_term\Entity\MembershipTermType::load',
      ],
      '#disabled' => !$membership_term_type->isNew(),
    ];

    $form['membership_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Membership Type'),
      '#options' => $membership_types,
      '#default_value' => $membership_term_type->getMembershipType(),
      '#description' => $this->t('Used by all membership terms of this type.'),
      '#required' => true,
    ];

    $form['term_length'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Membership Term Length'),
      '#maxlength' => 50,
      '#default_value' => $membership_term_type->getTermLength(),
      '#description' => $this->t('Length of active membership defined by this membership term. Set to 0 for lifetime.'),
      '#required' => true,
    ];

    $form['grace_period'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Grace Period'),
      '#maxlength' => 50,
      '#default_value' => $membership_term_type->getGracePeriod(),
      '#description' => $this->t('Revoke membership privileges how long after "official" expiration? Use strtotime modifiers -- e.g. "+1 day", "+2 months". Sets the "revoke_date" field. Leave blank to use the actual expiration date.'),
    ];

    $form['workflow'] = [
      '#type' => 'select',
      '#title' => t('Workflow'),
      '#options' => $workflows,
      '#default_value' => $membership_term_type->getWorkflowId(),
      '#description' => $this->t('Used by all membership terms of this type.'),
      '#required' => true,
    ];

    $form['messages'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Message template'),
        $this->t('Date Field'),
        $this->t('Offset'),
        $this->t('State'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('There are currently no scheduled messages for this term. Add one by selecting an option below.'),
      '#weight' => 5,
    ];
    /** @var \Drupal\message\MessageTemplateInterface $message */
    foreach ($this->entity->getMessages() as $message) {
      $key = $message->getUuid();
      $form['messages'][$key]['message'] = [
        '#tree' => FALSE,
        'data' => [
          'label' => [
            '#plain_text' => $message->getConfiguration()['data']['message'],
          ]
        ]
      ];

      $form['messages'][$key]['date_field'] = [
        '#plain_text' => $message->getConfiguration()['data']['date_field'],
      ];
      $form ['messages'][$key]['offset'] = [
        '#plain_text' => $message->getConfiguration()['data']['offset'],
      ];
      $form ['messages'][$key]['state'] = [
        '#plain_text' => $message->getConfiguration()['data']['state'],
      ];
      $links = [
        'edit' => [
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('scheduled_message.edit_form', [
            'entity_type' => $this->entity->getEntityTypeId(),
            'entity_id' => $this->entity->id(),
            'scheduled_message' => $key,
          ]),
        ],
        'delete' => [
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('scheduled_message.delete', [
            'entity_type' => $this->entity->getEntityTypeId(),
            'entity_id' => $this->entity->id(),
            'scheduled_message' => $key,
          ]),
        ],
      ];
      $form['messages'][$key]['operations'] = [
        '#type' => 'operations',
        '#links' => $links,
      ];

    }

    $form['messages']['new'] = [
      '#tree' => FALSE,
    ];
    $form['messages']['new']['message'] = [
      'data' => [
        'add' => [
          '#type' => 'submit',
          '#value' => $this->t('Add'),
          '#submit' => ['::submitForm', '::messageSave'],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Submit handler for Scheduled Message.
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function messageSave($form, FormStateInterface $form_state) {
    $this->save($form, $form_state);

    $message = $this->scheduledMessageManager->getDefinition('scheduled_email');
    $form_state->setRedirect(
      'scheduled_message.add_form',
      [
        'entity_type' => $this->entity->getEntityTypeId(),
        'entity_id' => $this->entity->id(),
        'scheduled_message' => $message['id'], //$form_state->getValue('new'),
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $membership_term_type = $this->entity;
    $status = $membership_term_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Membership term type.', [
          '%label' => $membership_term_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Membership term type.', [
          '%label' => $membership_term_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($membership_term_type->toUrl('collection'));
  }

}
