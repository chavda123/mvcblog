<?php 
include 'controller/post_ctl.php';
$objPost = new post_ctl();
$posts = $objPost->post_categories_ctl();

require_once 'header.php';
?>
            <!-- Search form -->
            <?php require_once 'search.php'; ?>     
            <div class="row tm-row">
                <?php if(!empty($posts['rows'])) : ?>
                <?php foreach ($posts['rows'] as $i => $post): ?>
                <article class="col-12 col-md-6 tm-post">
                    <hr class="tm-hr-primary">
                    <a href="<?php echo common::SITE_URL; ?>post.php?id=<?php echo $post['id']; ?>" class="effect-lily tm-post-link tm-pt-60">
                        <div class="tm-post-link-inner">
                            <img src="<?php echo common::SITE_URL; ?>uploads/<?php echo $post['image']; ?>" alt="Image" class="img-fluid">                            
                        </div>
                        <h2 class="tm-pt-30 tm-color-primary tm-post-title"><?php echo $post['title']; ?></h2>
                    </a>                    
                    <p class="tm-pt-30">
                    <?php echo $post['description']; ?>
                    </p>
                    <div class="d-flex justify-content-between tm-pt-45">
                        <span class="tm-color-primary"><?php echo $post['category_list']; ?></span>
                        <span class="tm-color-primary"><?php echo $post['published']; ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>by <?php echo $post['author']; ?></span>
                    </div>
                </article>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>No any post found!</p>
                <?php endif; ?>
            </div>
            <div class="row tm-row tm-mt-100 tm-mb-75">
                <div class="tm-paging-wrapper">
                    <span class="d-inline-block mr-3">Page</span>
                    <?php
                    $limit = isset($posts['result_per_page']) ? (int)$posts['result_per_page'] : 1;
                    $currentPage = isset($_GET['page_no']) ? (int)$_GET['page_no'] : 1; 
                    $totalPages = isset($posts['total_pages']) ? (int)$posts['total_pages'] : 1;
                    $cat = isset($_GET['cat']) ? (int)$_GET['cat'] : '';
                    
                    $baseUrl = common::SITE_URL . "category.php?cat=$cat&limit=$limit&page_no=";                    

                    echo '<nav class="tm-paging-nav d-inline-block">';
                    echo '<ul>';

                    for ($page = 1; $page <= $totalPages; $page++) {
                        $activeClass = $page === $currentPage ? 'active' : '';
                        echo '<li class="tm-paging-item ' . $activeClass . '">';
                        echo '<a href="' . $baseUrl . $page . '" class="mb-2 tm-btn tm-paging-link">' . $page . '</a>';
                        echo '</li>';
                    }

                    echo '</ul>';
                    echo '</nav>';
                    ?>
                </div>                
            </div>            
            <?php require_once 'footer.php'; ?>
            </body>
            </html>