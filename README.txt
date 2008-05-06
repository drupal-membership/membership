membership.module
=================
CSÉCSY László <boobaa@frugalware.org>
2008-05-05
:revision: 5.x-1.1

== Introduction

The membership module provides easy time-limited membership management.

== Installation

Simply untar the module to the usual place, set up the permissions at
*admin/user/access*, and select the role to has time-limited membership at
*admin/settings/membership*. You may want to select the Power Manager as well,
who will be sent notifies from those who have the _administer membership_
permission. The module relies upon Drupal's cron hooks, so it may be wise to
have the cron part (eg. *cron.php*) be run regularly.

== Permissions

The module introduces the following permissions.

*access membership*:: Allows using the basic functions like adding a user to the
pre-selected role in a time-limited manner.

*administer membership*:: Allows setting up the module like specifying the
time-limited role.

== Tasks

=== Administering the module

With the _administer membership_ permission one is expected to set up the module
before making use of it by the means of a form accessible at
*admin/settings/membership*. A role must be selected to have time-limited
membership management, with the following restrictions:

* basic Drupal roles (ie. _anonymous user_ and _authenticated user_) cannot be
selected;
* the time-limited role cannot be changed if it already has any members.

A Power Manager can also be selected from the users with _access membership_
role, who will be receiving notifications on all and any changes in the
time-limited role's membership.

=== List users with time-limited membership

With the _access membership_ permission one is allowed to list the users with
time-limited membership with the following columns:

* username,
* last modified,
* expires,
* operations.

The latter consists of two links: *Expire now* and *Edit membership*. The list
can be sorted by any of the first three columns. Under the list a form is shown
to add a new user to the time-limited role.

=== Add a user to the time-limited role

There are two fields to be filled: the username (with autocomplete, to make your
life easier) and the date of the expiration. Username is verified whether it
exists and whether he/she is already member of the time-limited role. Expiration
date must be in the future. If everything goes well, the specified user is added
to the time-limited role (thus to the list mentioned at the previous task), a
notification is sent to the user him/herself, and to the Power Manager, if
selected.

=== Remove a user from the time-limited role by hand (Expire now)

Clicking on an *Expire now* link immediately removes the specified user from the
time-limited role and the above-mentioned list (without any further
confirmation), sends a notification to the user him/herself, and to the Power
Manager, if selected.

=== Edit a user's membership information

Clicking on an *Edit membership* link leads to a form with only one date field,
which allows changing the time-limited membership expiration date of the
specified user. The date cannot be changed to be in the past. If everything goes
well, the specified user's membership info is changed (the last modification
time as well), a notification is sent to the user him/herself, and to the Power
Manager, if selected.

=== Automatic membership expiration

The module's cron hook is responsible to remove users from the time-limited
role when their membership expires. The affected users are sent a notification,
as well as the Power Manager, if selected.

== Database

As the time of writing only MySQL is supported.

=== *membership* table

Contains time-limited membership information with the following fields:

* *mid*: membership's unique ID;
* *uid*: member's \{users\}.uid;
* *uid*: owner's \{users\}.uid (the user who added this member);
* *lastmod*: membership's last modification time as a UNIX timestamp;
* *expires*: membership's expiration time as a UNIX timestamp.

== Views support

The module provides one field for views.module: "Membership: Expires", which
simply displays expiration date of author's membership. The option field may be
used to specify the custom date format as it's required by the date() function.

== Ideas to be implemented

* Add new user in a block - http://drupal.org/node/240359[]
* Log membership changes to the watchdog - http://drupal.org/node/237027[]
* Ability to change the notifications' text - http://drupal.org/node/237020[]
* Ability to set a date when all members' membership expires -
http://drupal.org/node/237018[]
* Prevent changing the time-limited role's membership through other (core
Drupal) forms.

// vim: set tw=80:
