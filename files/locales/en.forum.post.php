<?php
// Post Titles
$locale['400'] = "Preview Thread";
$locale['401'] = "Post Thread";
$locale['402'] = "Preview Reply";
$locale['403'] = "Reply to Thread";
$locale['404'] = "Post Reply";
$locale['405'] = "Preview Changes";
$locale['407'] = "Delete Post";
$locale['408'] = "Edit Post";
$locale['409'] = "Save Changes";
$locale['410'] = "Make Post Non-Sticky";
$locale['411'] = "Make Post Sticky";
$locale['412'] = "Move Post";
$locale['413'] = "Continue";
$locale['414'] = "Attachment upload";
$locale['415'] = "Reply to Post";
$locale['416'] = "Renew Post";
$locale['417'] = "Cancel";
$locale['418'] = "Cancel Post";
// Post Preview
$locale['420'] = "No Subject";
$locale['421'] = "No Message, Post will be rejected if you do not include a Message";
$locale['422'] = "Author:";
$locale['423'] = "Posts:";
$locale['424'] = "Location:";
$locale['425'] = "Joined:";
$locale['426'] = "Posted on ";
$locale['427'] = "Edited by ";
$locale['428'] = " on ";
$locale['429'] = " wrote:";
$locale['430'] = "download(s)";
// post flooding messages
$locale['431'] = "Error: This forum is protected against post flooding.";
$locale['432'] = "YOU ARE LOGGED OUT. YOUR ACCOUNT IS NOW SUSPENDED.";
$locale['433'] = "You are allowed to post one message per %u seconds";
$locale['434'] = "Suspended due to post flooding";
// Post Error/Success
$locale['439'] = "Your Post has been cancelled";
$locale['440'] = "Unknown error %u detected while uploading %s.";
$locale['440a'] = "Attachment file type not allowed.";
$locale['440b'] = "Invalid attachment filesize.";
$locale['440c'] = "Attached image file not recognized as a valid image.";
$locale['440d'] = "Possible file upload attack! Uploaded attachment has been discarded.";
$locale['441'] = "Error: You did not specify a Subject and/or Message";
$locale['442'] = "Your Thread has been Posted";
$locale['443'] = "Your Reply has been Posted";
$locale['444'] = "The Thread has been deleted";
$locale['445'] = "The Post has been deleted";
$locale['446'] = "Your Post has been updated";
$locale['447'] = "Return to Thread";
$locale['448'] = "Return to Forum";
$locale['449'] = "Return to Forum Index";
$locale['450'] = "Error: Your cookie session has expired, please login and repost";
$locale['451'] = "Track Thread";
$locale['452'] = "You are now tracking this thread";
$locale['453'] = "You are no longer tracking this thread";
$locale['454'] = "This post has now been marked 'sticky' and has been moved to the start of the thread";
$locale['455'] = "The 'sticky' marker has been removed from this post";
$locale['456'] = "This post has been moved to the selected thread";
$locale['457'] = "Back to your post";
$locale['458'] = "Error: Your have already posted this message. Did you use the back button?";
$locale['459'] = "The post time of this post has been set to the current time";
//// Post Form
$locale['460'] = "Subject";
$locale['461'] = "Message";
$locale['462'] = "Font Color: ";
$locale['463'] = "Options";
$locale['464'] = "Attachment(s)";
$locale['465'] = " (Optional)";
$locale['466'] = "Max. filesize: %s.<br />Illegal file extensions: %s.";
$locale['467'] = "Toggle smileys";
$locale['468'] = "Smileys";
$locale['469'] = "Add a Poll";
$locale['470'] = "Sticky Post";
$locale['471'] = "Upload attachment";
$locale['472'] = array('maroon', 'red', 'orange', 'brown', 'yellow', 'green', 'lime', 'olive', 'cyan', 'blue', 'navy', 'purple', 'violet', 'black', 'gray', 'silver', 'white');
$locale['473'] = "Comments (Max.length: 255 characters):";
// Post Form Options
$locale['480'] = " Make this Thread Sticky";
$locale['481'] = " Show My Signature in this Post";
$locale['482'] = " Delete this Post";
$locale['483'] = " Disable Smileys in this Post";
$locale['484'] = " Delete attachment:";
$locale['485'] = " Notify me when a reply is posted";
$locale['486'] = " Move post to forum";
$locale['487'] = " Move post to thread";
$locale['488'] = " Remove uploaded attachment:";
// Post Access Violation
$locale['500'] = "You cannot edit this post.";
$locale['501'] = "You cannot move a post that has a poll attached to it.";
// Search Forum Form
$locale['530'] = "Search Forum";
$locale['531'] = "Search Keyword(s)";
$locale['532'] = "Search";
// Forum Notification Email
$locale['550'] = "Thread Reply Notification - {THREAD_SUBJECT}";
$locale['551'] = "Hello {USERNAME},

A reply has been posted in the forum thread '{THREAD_SUBJECT}' which you are tracking at ".html_entity_decode($settings['sitename']).". You can use the following link to view the reply:

{THREAD_URL}

If you no longer wish to watch this thread you can click the 'Tracking Off' button located at the bottom of the thread.

Regards,
".html_entity_decode($settings['siteusername']).".";
?>