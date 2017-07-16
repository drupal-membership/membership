

## Reminder emails

Reminders are created using the Message module, and sent via Message Notify.

Reminders use a compound field consisting of:

1. Message template to send
2. Message term date to use
3. # of day offset from this date to schedule message
4. Term state(s) when message should be queued.

Message entities are created when the membership term is created or updated:

### Term creation:

Loop through all reminders:
  - Create message entity from template
  - Associate membership_term
  - Set state_machine state to "pending"
  - Set date to send message

### Term update:

Load all messages for term
Loop through all reminders:
  - If reminder is still relevant and message exists, update message.
  - If message does not exist, create message as above.
  - If unsent message is no longer relevant, delete.

### Term type update:

Queue an update for all terms of type.

### Date arrives

Cron job transitions all "pending" messages with past date to "queued" and queues messages.

Queue worker sends messages and sets state to "sent"