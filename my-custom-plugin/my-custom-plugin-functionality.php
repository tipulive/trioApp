<?php

add_shortcode('my_comment_form', 'my_comment_form_shortcode');

// Define the shortcode callback function
function my_comment_form_shortcode() {
    ob_start(); // Start output buffering

    // Process form submission
    if (isset($_POST['submit_comment'])) {
        // Retrieve comment data from the form
        $comment_author = sanitize_text_field($_POST['comment_author']);
		 $comment_email = sanitize_email($_POST['comment-email']);
        $comment_content = sanitize_text_field($_POST['comment_content']);

 $comment_data = array(
            'comment_author' => $comment_author,
			 'comment_author_email' => $comment_email,
            'comment_content' => $comment_content,
            'comment_approved' => 1, // Set to 1 to auto-approve the comment
        );
     
		 if(sendData($comment_data))
		 {
			  $comment_id = wp_insert_comment($comment_data);
			  
			  
        if ($comment_id) {
            echo '<div class="success-message" style="color:green">Comment posted successfully!</div>';
        } else {
            echo '<div class="success-message" style="color:red">Error posting comment. Please try again.</div>';
        }
			
		 }
		 else{
			 echo '<div class="success-message" style="color:red">Error posting Data in API Please try again.</div>';
		 }
		




        
    }

    // Display the form
    ?>
    <form method="POST">
        <p>
            <label for="comment_author">Name:</label>
            <input type="text" name="comment_author" id="comment_author" required>
        </p>
		<p>
            <label for="comment-email">Email:</label>
            <input type="email" name="comment-email" id="comment-email" required>
        </p>
        <p>
            <label for="comment_content">Comment:</label>
            <textarea name="comment_content" id="comment_content" required></textarea>
        </p>
        <p>
            <input type="submit" name="submit_comment" value="Post Comment">
        </p>
    </form>
    <?php

    return ob_get_clean(); // Return the output
}

function sendData($comment_data){//send Data in Api
	
//echo $comment_data['comment_author'];

$data = array(
     'name' =>$comment_data['comment_author'],
    'email' =>$comment_data['comment_author_email'],
	'comment' =>$comment_data['comment_content']
);

$response = wp_remote_post('http://localhost:8000/api/saveComment', array(
    'method' => 'POST',
    'headers' => array(
        'Content-Type' => 'application/json',
    ),
    'body' => wp_json_encode($data),
));

if (is_wp_error($response)) {
    // Handle the error
    $error_message = $response->get_error_message();
    echo "API request failed: $error_message";
} else {
    // Get the response body
    //$response_body = wp_remote_retrieve_body($response);
	
	return true;
   
}


	
	
}
