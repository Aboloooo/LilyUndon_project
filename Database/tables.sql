drop DATABASE Don_Bosco;
CREATE DATABASE Don_Bosco;
USE Don_Bosco;


CREATE TABLE users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    First_name VARCHAR(255),
    Last_name VARCHAR(255),
    social_security_number BIGINT NOT NULL,
    Username VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    Level VARCHAR(255),
    user_must_change_password BOOLEAN
    /* Zero is considered as false, nonzero values are considered as true. */
);


/* INSERT INTO users (First_name, Last_name, social_security_number, Username, Password, Email, Level, user_must_change_password)
VALUES
("abolo", "ahmadi", "2003122601327", "admin", "password", "admin@gmail.com", "aDmin", 0),
("abolo", "ahmadi", "2003122601328", "abolo123", "123", "abolo@gmail.com", "cusTomer", 1);
 */

CREATE TABLE IF NOT EXISTS reservation (
    ReservationID INT PRIMARY KEY AUTO_INCREMENT,
    Reserved_by_userID INT,
    StartMoment DATETIME,
    FOREIGN KEY (Reserved_by_userID) REFERENCES users(UserID) ON DELETE CASCADE
);

/* 
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-04-28 12:00");
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-04-30 10:00");
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-05-03 08:00"); 
*/

CREATE TABLE translation (
    translationID VARCHAR(99) PRIMARY KEY,
    en VARCHAR(999),
    fr VARCHAR(999),
    de VARCHAR(999)
);
INSERT INTO translation (translationID, en, fr, de) VALUES
('cns_username_error', 
  'Error: The CNS number or username has already been used. Please enter a unique value.', 
  'Erreur : Le numéro CNS ou le nom d/utilisateur a déjà été utilisé. Veuillez saisir une valeur unique.', 
  'Fehler: Die CNS-Nummer oder der Benutzername wurde bereits verwendet. Bitte geben Sie einen eindeutigen Wert ein.'
),
('user_created_successfully', 
  'User created successfully!', 
  'Utilisateur créé avec succès!', 
  'Benutzer erfolgreich erstellt!'
),
('passwords_not_match', 
  'Passwords do not match!', 
  'Les mots de passe ne correspondent pas !', 
  'Die Passwörter stimmen nicht überein!'
),
('welcome_message', 
  'Welcome to Croix-Rouge', 
  'Bienvenue à la Croix-Rouge', 
  'Willkommen bei Croix-Rouge'
),
('form_instruction', 
  'Fill in the form below to register a new user in the system. Make sure all required fields are completed accurately.', 
  'Remplissez le formulaire ci-dessous pour enregistrer un nouvel utilisateur dans le système. Assurez-vous que tous les champs obligatoires sont remplis correctement.', 
  'Füllen Sie das folgende Formular aus, um einen neuen Benutzer im System zu registrieren. Stellen Sie sicher, dass alle Pflichtfelder korrekt ausgefüllt sind.'
),
('first_name', 
  'First name', 
  'Prénom', 
  'Vorname'
),
('last_name', 
  'Last Name', 
  'Nom de famille', 
  'Nachname'
),
('social_security_number', 
  'Social Security Number', 
  'Numéro de sécurité sociale', 
  'Sozialversicherungsnummer'
),
('username', 
  'Username', 
  'Nom d/utilisateur', 
  'Benutzername'
),
('password', 
  'Password', 
  'Mot de passe', 
  'Passwort'
),
('password_confirmation', 
  'Password Confirmation', 
  'Confirmation du mot de passe', 
  'Passwortbestätigung'
),
('email', 
  'Email', 
  'Email', 
  'E-Mail'
),
('sign_in', 
  'Sign In', 
  'Se connecter', 
  'Anmelden'
),
('add_user', 'Add User', 'Ajouter un utilisateur', 'Benutzer hinzufügen'),

/* index */
('home', 'Home', 'Accueil', 'Startseite'),
('cook', 'Cook', 'Cuisinier', 'Koch'),
('enjoy', 'Enjoy', 'Profitez', 'Genießen'),
('Logout', 'Logout', 'Déconnexion', 'Abmelden'),
('Reserve', 'Reserve', 'Réserver', 'Reservieren'),

/* logout or in */
('login_logout', 'Login/Logout', 'Connexion/Déconnexion', 'Anmelden/Abmelden'),
('password_incorrect', 'Password is incorrect!', 'Le mot de passe est incorrect !', 'Das Passwort ist falsch!'),
('username_not_found', 'Username could not be found!', 'Nom d/utilisateur introuvable !', 'Benutzername konnte nicht gefunden werden!'),
('initial_password_change_required', 'Your initial password needs to be changed. Please change your password and try again.', 'Votre mot de passe initial doit être changé. Veuillez le modifier et réessayer.', 'Ihr Anfangspasswort muss geändert werden. Bitte ändern Sie Ihr Passwort und versuchen Sie es erneut.'),
('secure_account', 'Secure your account. Please update your password and keep your information safe.', 'Sécurisez votre compte. Veuillez mettre à jour votre mot de passe et protéger vos informations.', 'Sichern Sie Ihr Konto. Bitte aktualisieren Sie Ihr Passwort und schützen Sie Ihre Daten.'),
('welcome_tagline', 'Empowering communities with care. Please sign in to manage your reservations and services.', 'Autonomiser les communautés avec bienveillance. Veuillez vous connecter pour gérer vos réservations et services.', 'Gemeinschaften mit Fürsorge stärken. Bitte melden Sie sich an, um Ihre Reservierungen und Dienste zu verwalten.'),
('forgot_password', 'Forgot Password?', 'Mot de passe oublié ?', 'Passwort vergessen?'),
('or_sign_in_with', 'Or sign in with', 'Ou connectez-vous avec', 'Oder anmelden mit'),
('sign_up', 'Sign Up', 'S/inscrire', 'Registrieren'),
('no_account', 'Don/t have an account?', 'Vous n/avez pas de compte ?', 'Sie haben noch kein Konto?'),
('password_updated', 'Password updated successfully.', 'Mot de passe mis à jour avec succès.', 'Passwort erfolgreich aktualisiert.'),
('password_update_failed', 'Failed to update password', 'Échec de la mise à jour du mot de passe', 'Fehler beim Aktualisieren des Passworts'),
('new_passwords_do_not_match', 'New passwords do not match!', 'Les nouveaux mots de passe ne correspondent pas !', 'Die neuen Passwörter stimmen nicht überein!'),
('current_password_incorrect', 'Your current password is incorrect', 'Votre mot de passe actuel est incorrect', 'Ihr aktuelles Passwort ist falsch'),
('user_not_found', 'User not found', 'Utilisateur non trouvé', 'Benutzer nicht gefunden'),
('all_fields_required', 'All fields are required', 'Tous les champs sont obligatoires', 'Alle Felder sind erforderlich'),
('current_password', 'Current Password', 'Mot de passe actuel', 'Aktuelles Passwort'),
('new_password', 'New Password', 'Nouveau mot de passe', 'Neues Passwort'),
('confirm_new_password', 'Confirm New Password', 'Confirmer le nouveau mot de passe', 'Neues Passwort bestätigen'),
('update_password', 'Update Password', 'Mettre à jour le mot de passe', 'Passwort aktualisieren'),

/* my reservation */
('my_reservations', 'My Reservations', 'Mes réservations', 'Meine Reservierungen'),
('reservation_cancelled_successfully', 'Reservation cancelled successfully', 'Réservation annulée avec succès', 'Reservierung erfolgreich storniert'),
('cancellation_failed', 'Cancellation failed', 'Échec de l/annulation', 'Stornierung fehlgeschlagen'),
('my_kitchen_reservations', 'My Kitchen Reservations', 'Mes réservations de cuisine', 'Meine Küchenreservierungen'),
('date', 'Date', 'Date', 'Datum'),
('time_slot', 'Time Slot', 'Créneau horaire', 'Zeitfenster'),
('confirm_cancel_reservation', 'Are you sure you want to cancel this reservation?', 'Êtes-vous sûr de vouloir annuler cette réservation ?', 'Sind Sie sicher, dass Sie diese Reservierung stornieren möchten?'),
('cancel', 'Cancel', 'Annuler', 'Abbrechen'),
('no_reservations_yet', 'You have no reservations yet.', 'Vous n/avez pas encore de réservations.', 'Sie haben noch keine Reservierungen.'),
('please_login_to_view_reservations', 'Please login to display the reservations.', 'Veuillez vous connecter pour afficher les réservations.', 'Bitte melden Sie sich an, um die Reservierungen anzuzeigen.'),

/* reserve */
('kitchen_reservation_calendar', 'Kitchen Reservation Calendar', 'Calendrier de réservation de cuisine', 'Küchenreservierungskalender'),
('already_have_reservation_on_day', 'You already have a reservation on this day!', 'Vous avez déjà une réservation ce jour-là !', 'Sie haben an diesem Tag bereits eine Reservierung!'),
('reservation_done_successfully', 'Reservation done successfully!', 'Réservation effectuée avec succès !', 'Reservierung erfolgreich durchgeführt!'),
('error', 'Error', 'Erreur', 'Fehler'),
('reservation_limit_exceeded', 'You cannot reserve more than 4 times per week!', 'Vous ne pouvez pas réserver plus de 4 fois par semaine !', 'Sie können nicht mehr als 4 Mal pro Woche reservieren!'),
('please_login_first', 'Please login first!', 'Veuillez vous connecter d/abord !', 'Bitte zuerst anmelden!'),
('time', 'Time', 'Heure', 'Uhrzeit'),
('reserved', 'Reserved', 'Réservé', 'Reserviert'),
('confirm_reserve_time', 'Are you sure you want to reserve this time?', 'Êtes-vous sûr de vouloir réserver ce créneau ?', 'Sind Sie sicher, dass Sie diese Zeit reservieren möchten?'),
('past', 'Past', 'Passé', 'Vergangenheit'),
('available', 'Available', 'Disponible', 'Verfügbar'),
('Monday', 'Monday', 'Lundi', 'Montag'),
('Tuesday', 'Tuesday', 'Mardi', 'Dienstag'),
('Wednesday', 'Wednesday', 'Mercredi', 'Mittwoch'),
('Thursday', 'Thursday', 'Jeudi', 'Donnerstag'),
('Friday', 'Friday', 'Vendredi', 'Freitag'),
('Saturday', 'Saturday', 'Samedi', 'Samstag'),
('Sunday', 'Sunday', 'Dimanche', 'Sonntag'),
('reserved_by_user_id', 'Reserved by user_ID', 'Réservé par l/ID utilisateur', 'Reserviert durch Benutzer-ID'),

/* users */
('users', 'Users', 'Utilisateurs', 'Benutzer'),
('registered_users', 'Registered Users', 'Utilisateurs enregistrés', 'Registrierte Benutzer'),
('user_id', 'User ID', 'ID utilisateur', 'Benutzer-ID'),
('CNS_number', 'CNS Number', 'Numéro CNS', 'CNS-Nummer'),
('role', 'Role', 'Rôle', 'Rolle'),
('changed_pass', 'Changed Pass', 'Mot de passe changé', 'Passwort geändert'),
('action', 'Action', 'Action', 'Aktion'),
('delete', 'Delete', 'Supprimer', 'Löschen'),
('true', 'True', 'vrai', 'wahr'),
('false', 'False', 'faux', 'falsch'),
('confirm_delete_user', 'Delete this user?', 'Supprimer cet utilisateur ?', 'Diesen Benutzer löschen?')


;
