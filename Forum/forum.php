<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        header {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #1B413A;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        
        .logo {
            height: 110px;
            width: auto;
        }
        .title {
            margin-left: 10px;
            font-size: 2rem;
            color: #FFFFFF;
        }
        body {
            margin: 0;
            background-color: #fff;
        }
        .go-back-btn {
            margin-top: 20px;
        }
/* Modal container styling */
    #imageModal {
        display: none; /* Ensure it's hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #modalImage {
        max-width: 90%;
        max-height: 90%;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        object-fit: contain;
    }

    .done-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 15px;
        font-size: 16px;
        color: #ffffff;
        background-color: #333;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        z-index: 1001; /* Ensure button is above modal image */
    }

    .done-btn:hover {
        background-color: #555;
    }
    </style>
</head>
<body>
    <header>
        <img src="../../pics/logo.png" alt="Logo" class="logo">
        <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
    </header>

    <div class="container mt-5">
        <?php
        session_start();
        include('../config/mysql.php');

        $goBackLink = isset($_SESSION['id']) ? "../../User/Member/report1.php" : "../../User/Guest/report.php";
        echo '<div class="go-back-btn"><a href="' . $goBackLink . '" class="btn btn-secondary">Go Back</a></div>';

        if (isset($_SESSION['id'])) {
            echo '<div class="text-center mt-4">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#postModal">Write a post...</button>
                  </div>';
        } else {
            echo '<br><div class="alert alert-warning">To write a post, please login by clicking <a href="../../User/member.php" style="font-weight:bold; text-decoration:underline;">here</a>.</div>';
        }
        ?>

        <div class="modal fade" id="postModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Write a new post</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="forum-process.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="postContent">Content</label>
                                <textarea class="form-control" id="postContent" name="content" rows="5" placeholder="Write your post here..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="fileUpload">Upload a file</label>
                                <input type="file" class="form-control-file" id="fileUpload" name="file" accept="image/*, video/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <?php
            $current_time = time();

            $query = "
                SELECT f.id, f.post, f.file, f.user_id, f.timestamp, l.name
                FROM forum f
                JOIN login l ON f.user_id = l.id
                ORDER BY f.id DESC
            ";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $post_time = strtotime($row['timestamp']);
                    if (abs($current_time - $post_time) <= 259200) {
                        echo '<div class="card mb-3"><div class="card-body">';
                        echo '<h5 class="card-title">Anonymous Person ' . htmlspecialchars($row['user_id']) . '</h5>';

                        $time_diff = $current_time - $post_time;
                        $time_display = $time_diff < 60 ? $time_diff . ' seconds ago' :
                                        ($time_diff < 3600 ? floor($time_diff / 60) . ' minutes ago' :
                                        ($time_diff < 86400 ? floor($time_diff / 3600) . ' hours ago' :
                                        floor($time_diff / 86400) . ' days ago'));
                        $formattedDate = date('F j, Y, g:i a', $post_time);
                        echo '<p class="text-muted" title="' . $formattedDate . '" style="font-size: 0.7em;">' . $time_display . '</p>';
                        
                        echo '<p class="card-text">' . htmlspecialchars($row['post']) . '</p>';

                        if (!empty($row['file'])) {
    $fileData = $row['file'];
    $mimeType = '';

    if (substr($fileData, 0, 3) == "\xff\xd8\xff") $mimeType = 'image/jpeg';
    elseif (substr($fileData, 0, 8) == "\x89PNG\x0d\x0a\x1a\x0a") $mimeType = 'image/png';
    elseif (substr($fileData, 0, 6) == "GIF89a" || substr($fileData, 0, 6) == "GIF87a") $mimeType = 'image/gif';
    elseif (strpos($fileData, 'ftyp') !== false) $mimeType = 'video/mp4';

    if ($mimeType) {
        if (strpos($mimeType, 'image/') === 0) {
            echo '<img src="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" class="img-fluid" alt="Post Media" onclick="openImageModal(this.src)" style="cursor: pointer;">';
        } else {
            echo '<video controls class="img-fluid"><source src="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" type="' . $mimeType . '">Your browser does not support the video tag.</video>';
        }
    } else {
        echo '<p>Unsupported file type.</p>';
    }
} else {
    echo '<p>No media available.</p>';
}

                        echo '<h6>Comments:</h6>';

                        $post_id = $row['id'];
                        $comment_query = "SELECT c.comment, c.timestamp, c.user_id FROM comments c WHERE c.post_id = ? ORDER BY c.timestamp DESC";
                        $comment_stmt = $conn->prepare($comment_query);
                        $comment_stmt->bind_param("i", $post_id);
                        $comment_stmt->execute();
                        $comment_result = $comment_stmt->get_result();

                        if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                            while ($comment_row = $comment_result->fetch_assoc()) {
                                echo '<div class="comment">';
                                echo '<p><strong>Anonymous Person ' . htmlspecialchars($comment_row['user_id']) . ':</strong> ' . htmlspecialchars($comment_row['comment']) . '</p>';

                                $comment_time = strtotime($comment_row['timestamp']);
                                $comment_time_diff = $current_time - $comment_time;
                                $comment_time_display = $comment_time_diff < 60 ? $comment_time_diff . ' seconds ago' :
                                                        ($comment_time_diff < 3600 ? floor($comment_time_diff / 60) . ' minutes ago' :
                                                        ($comment_time_diff < 86400 ? floor($comment_time_diff / 3600) . ' hours ago' :
                                                        floor($comment_time_diff / 86400) . ' days ago'));
                                echo '<p class="text-muted" style="font-size: 0.7em;">' . $comment_time_display . '</p>';
                                echo '</div>';
                            }
                        }

                        if (isset($_SESSION['id'])) {
                            echo '<form action="comment-process.php" method="POST">
                                    <input type="hidden" name="post_id" value="' . $post_id . '">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="comment" placeholder="Write a comment..." required>
                                    </div>
                                    <button type="submit" class="btn btn-secondary">Comment</button>
                                  </form>';
                        } else {
                            echo '<div class="alert alert-warning">To comment, please login by clicking <a href="../User/member.php" style="font-weight:bold; text-decoration:underline;">here</a>.</div>';
                        }

                        echo '</div></div>';
                    }
                }
            } else {
                echo '<p>No posts found.</p>';
            }
            ?>

            <!-- Modal for displaying enlarged image -->
<div id="imageModal" style="display: none;">
    <img id="modalImage" src="" alt="Enlarged Image">
    <button class="done-btn" onclick="closeImageModal()">Done</button>
</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        modal.style.display = 'flex'; // Show the modal
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none'; // Hide the modal
    }

    // Close the modal when clicking outside the image
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeImageModal();
        }
    }
</script>
</body>
</html>
