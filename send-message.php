<?php

// Check if the request is a POST request and if it contains the "username" and "message" parameters
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['message'])) {
  // Get the username and message from the request
  $username = $_POST['username'];
  $message = $_POST['message'];

  // Connect to the database
  $db = new PDO('mysql:host=localhost;dbname=chatroom;charset=utf8', 'username', 'password');

  // Insert the message into the database
  $query = $db->prepare('INSERT INTO messages (username, message) VALUES (:username, :message)');
  $query->bindValue(':username', $username, PDO::PARAM_STR);
  $query->bindValue(':message', $message, PDO::PARAM_STR);
  $query->execute();
}

// Check if the request is a GET request and if it contains the "last_message_id" parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['last_message_id'])) {
  // Get the ID of the last message that the user has received
  $lastMessageId = $_GET['last_message_id'];

  // Connect to the database
  $db = new PDO('mysql:host=localhost;dbname=chatroom;charset=utf8', 'username', 'password');

  // Retrieve all messages that have an ID greater than the last message ID that the user has received
  $query = $db->prepare('SELECT * FROM messages WHERE id > :last_message_id');
  $query->bindValue(':last_message_id', $lastMessageId, PDO::PARAM_INT);
  $query->execute();

  // Fetch the messages from the database
  $messages = $query->fetchAll(PDO::FETCH_ASSOC);

  // Return the messages as a JSON object
  header('Content-Type: application/json');
  echo json_encode(['messages' => $messages]);
}
