<?php 
include 'controller/post_ctl.php';
$objPost = new post_ctl();
$post = $objPost->post_get_ctl();

include 'controller/category_ctl.php';
$objCat = new category_ctl();
$categories = $objCat->category_select_ctl();

require_once 'header.php';
?>
            <style>.tm-post-link-inner { background: unset; }</style>
            <!-- Search form -->
            <?php require_once 'search.php'; ?>            
            <div class="row tm-row">
                <div class="col-12">
                    <hr class="tm-hr-primary tm-mb-55">
                    <div class="tm-post-link-inner">
                        <img src="<?php echo common::SITE_URL; ?>uploads/<?php echo $post['image']; ?>" alt="Image" class="img-fluid">                            
                    </div>
                </div>
            </div>
            <div class="row tm-row">
                <div class="col-lg-8 tm-post-col">
                    <div class="tm-post-full">                    
                        <div class="mb-4">
                            <h2 class="pt-2 tm-color-primary tm-post-title"><?php echo $post['title']; ?></h2>
                            <p class="tm-mb-40"><?php echo $post['published']; ?> posted by <?php echo $post['author']; ?></p>
                            <p><?php echo $post['description']; ?></p>
                            <span class="d-block text-right tm-color-primary"><?php echo $post['category_list']; ?></span>
                        </div>
                        
                        <!-- Comments -->
                        <div>
                            <h2 class="tm-color-primary tm-post-title">Comments</h2>
                            <?php if(!empty($post['comments'])) : ?>
                                <?php foreach($post['comments'] as $comment) : ?>
                            <hr class="tm-hr-primary tm-mb-45">
                            <div class="tm-comment tm-mb-45">
                                <figure class="tm-comment-figure">
                                    <img src="<?php echo common::SITE_URL ?>img/3.png" alt="Image" class="mb-2 rounded-circle img-thumbnail">
                                    <figcaption class="tm-color-primary text-center"><?php echo $comment['author'] ?></figcaption>
                                </figure>
                                <div>
                                    <p>
                                        <?php echo $comment['comment'] ?>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <span class="tm-color-primary"><?php echo $comment['published'] ?></span>
                                    </div>                                                 
                                </div>                                
                            </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <form action="" class="mb-5 tm-comment-form">
                                <h2 class="tm-color-primary tm-post-title mb-4">Your comment</h2>
                                <div class="alert alert-success d-none" id="success-msg" role="alert"></div>
                                <div class="mb-4">
                                    <input class="form-control" id="name" name="name" placeholder="Enter Name" type="text">
                                    <span class="error small-text" id="nameError"></span>
                                </div>
                                <div class="mb-4">
                                    <input class="form-control" id="email" placeholder="Enter Email" name="email" type="text">
                                    <span class="error small-text" id="emailError"></span>
                                </div>
                                <div class="mb-4">
                                    <textarea class="form-control" id="message" placeholder="Enter Message" name="message" rows="6"></textarea>
                                    <span class="error small-text" id="messageError"></span>
                                </div>
                                <input type="hidden" name="postid" id="postid" value="<?php echo common::getVal('id'); ?>"/>
                                <div class="text-right">
                                    <button type="button" id="submit-comment" class="tm-btn tm-btn-primary tm-btn-small">Submit</button>                        
                                </div>                                
                            </form>                          
                        </div>
                    </div>
                </div>
                <aside class="col-lg-4 tm-aside-col">
                    <div class="tm-post-sidebar">
                        <hr class="mb-3 tm-hr-primary">
                        <h2 class="mb-4 tm-post-title tm-color-primary">Categories</h2>
                        <ul class="tm-mb-75 pl-5 tm-category-list">
                            <?php foreach ($categories['rows'] as $i => $category): ?>
                            <li><a href="category.php?cat=<?php echo $category['id'] ?>" class="tm-color-primary"><?php echo $category['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>                    
                </aside>
            </div>
            <?php require_once 'footer.php'; ?>
            <script>
            function checkName(name) {
            if (!/^[a-zA-Z\s-]+$/.test(name))
                return false;
            return true;
            }

            function checkEmail(email) {
            if (!/^.+@.+\..+$/.test(email))
                return false;
            return true;
            }

            function isErrors() {
            var errors = {}, isErrors = false;
            var name = $('#name').val();
            var email = $('#email').val();
            var comment = $('#message').val();
            $('#nameError').html('');
            $('#emailError').html('');
            $('#messageError').html('');
            if (name.length == 0) {
                $('#nameError').html("Please enter full name");
                isErrors = true;
            } else if (!this.checkName(name)) {
                $('#nameError').html("Please enter valid full name. Special characters are not allowed.");
                isErrors = true;
            }
            if (email.length == 0) {
                $('#emailError').html("Please enter email address");
                isErrors = true;
            } else if (!this.checkEmail(email)) {
                $('#emailError').html("Please enter valid email adddress.");
                isErrors = true;
            }
            if (comment.length == 0) {
                $('#messageError').html("Please enter comment text");
                isErrors = true;
            }
            
            return isErrors;
            }

            $("#submit-comment").click(function() {
            var name = $('#name').val();
            var email = $('#email').val();
            var comment = $('#message').val();
            var postid = $('#postid').val();
            const csrfToken = '<?php echo common::csrf_token(); ?>';
            if(!isErrors()) {
                $.ajax({
                url: "post.php?id="+postid,
                type: "POST",
                data: {
                    method: "add_comment",
                    postid: postid,
                    name: name,
                    email: email,
                    comment: comment,
                    csrfToken: csrfToken,
                },
                success: function(response) {
                    if(response.isSuccess == 1) {
                        window.location.href="post.php?id="+postid;
                        $('#success-msg').removeClass('d-none');
                        $('#success-msg').html(response.msg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", status, error);
                }
                });
            }
            });
            </script>
            </body>
            </html>