<?php 
use yii\widgets\LinkPager; 

$this->title = '首页';
$this->params['breadcrumbs'][] = $this->title;
?>
        <div class="row">
            <div class="span12">
                <div class="row">
                    <!-- page content -->
                    <section id="page-sidebar" class="alignleft span8">
                        <!-- content -->
                        <div class="row">
                            <div class="span8">
                                <div class="title-divider">
                                    <h3>Portfolio</h3>
                                    <div class="divider-arrow"></div>
                                </div>
                            </div>
                            <article class="blog-post span4">
                                <div class="block-grey">
                                    <div class="block-light">
                                        <a href="./portfolio2.html"><img src="<?php echo $this->theme->baseUrl; ?>/example/latest1.jpg" alt="photo" /></a>
                                        <div class="wrapper">
                                            <h2 class="post-title"><a href="#">Lorem ipsum</a></h2>
                                            <a href="#" class="blog-comments">3</a>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                                                sed diam nonummy nibh euismod tdolore mag quam erat volutpat.
                                                <a href="#" class="read-more">[...]</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="blog-post span4">
                                <div class="block-grey">
                                    <div class="block-light">
                                        <a href="./portfolio2.html"><img src="<?php echo $this->theme->baseUrl; ?>/example/latest2.jpg" alt="photo" /></a>
                                        <div class="wrapper">
                                            <h2 class="post-title"><a href="#">Lorem ipsum</a></h2>
                                            <a href="#" class="blog-comments">3</a>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                                                sed diam nonummy nibh euismod tdolore mag quam erat volutpat.
                                                <a href="#" class="read-more">[...]</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="blog-post span4">
                                <div class="block-grey">
                                    <div class="block-light">
                                        <a href="./portfolio2.html"><img src="<?php echo $this->theme->baseUrl; ?>/example/latest3.jpg" alt="photo" /></a>
                                        <div class="wrapper">
                                            <h2 class="post-title"><a href="#">Lorem ipsum</a></h2>
                                            <a href="#" class="blog-comments">3</a>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                                                sed diam nonummy nibh euismod tdolore mag quam erat volutpat.
                                                <a href="#" class="read-more">[...]</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="blog-post span4">
                                <div class="block-grey">
                                    <div class="block-light">
                                        <a href="./portfolio2.html"><img src="<?php echo $this->theme->baseUrl; ?>/example/latest5.jpg" alt="photo" /></a>
                                        <div class="wrapper">
                                            <h2 class="post-title"><a href="#">Lorem ipsum</a></h2>
                                            <a href="#" class="blog-comments">3</a>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                                                sed diam nonummy nibh euismod tdolore mag quam erat volutpat.
                                                <a href="#" class="read-more">[...]</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <!-- special offer -->
                        <div class="row">
                            <div class="span8">
                                <div class="title-divider">
                                    <h3>Special Offer</h3>
                                    <div class="divider-arrow"></div>
                                </div>
                            </div>
                            <div class="span8">
                                <div class="block-grey wrap15">
                                    <section id="welcome">
                                        <div class="row">
                                            <div class="span5">
                                                <h1>Lorem Ipsum Dolor Sit Amet</h1>
                                                <p class="last">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut lagna aliquam erat volutpat.</p>
                                            </div>
                                            <div class="span2 clearfix">
                                                <a class="btn btn-success btn-large" href="#">Buy Now</a>
                                            </div>
                                        </div>


                                    </section>
                                </div>
                            </div>
                            <div class="spacer"></div>
                        </div>
                        <!-- education news -->
                        <div class="row">
                            <div class="span8 education-news">
                                <div class="title-divider">
                                    <h3>Education News</h3>
                                    <div class="divider-arrow"></div>
                                </div>
                                <div class="block-grey">
                                    <div class="block-light wrap15">
                                        <div class="row">
                                            <div class="span2">
                                                <a href="./portfolio2.html"><img alt="photo" src="<?php echo $this->theme->baseUrl; ?>/example/latest6.jpg" /></a>
                                            </div>
                                            <div class="span5">
                                                <h2 class="post-title"><a href="#">Lorem ipsum</a></h2>
                                                <a class="blog-comments" href="#">3</a>
                                                <p>
                                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tdolore mag quamr sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tdolore mag quam erat volutpat.
                                                    <a class="read-more" href="#">[...]</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wrap15 clearfix">
                                        <div class="percent-50">
                                            <ul class="color2 list-style-2">
                                                <li>Lorem ipsum dolor sit amet, consectetuer</li>
                                                <li>Sed diam nonummy nibh euismod tdolore</li>
                                                <li>Sit amet, consectetuer adipiscing elit, sed</li>
                                                <li>Nummy nibh euismod tdolore mag quam</li>
                                            </ul>
                                        </div>
                                        <div class="percent-50">
                                            <ul class="color2 list-style-2">
                                                <li>Lorem ipsum dolor sit amet, consectetuer</li>
                                                <li>Sed diam nonummy nibh euismod tdolore</li>
                                                <li>Sit amet, consectetuer adipiscing elit, sed</li>
                                                <li>Nummy nibh euismod tdolore mag quam</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer"></div>
                        </div>
                        <!-- portfolio slider -->
                        <div class="row">
                            <div class="span8">
                                <div class="title-divider">
                                    <h3>Portfolio Slider</h3>
                                    <div class="divider-arrow"></div>
                                </div>
                            </div>
                            <div class="span8">
                                <div class="block-grey our-portfolio">
                                    <div class="block-light wrap10">
                                        <div id="latest-work" class="carousel btleft">
                                        <div class="carousel-wrapper">
                                            <ul class="da-thumbs-folio">
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest1.jpg" />
                                                    <h3>Creative Ideas</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest2.jpg" />
                                                    <h3>Twitter? or...</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest3.jpg" />
                                                    <h3>water painting</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest4.jpg" />
                                                    <h3>Creative Ideas</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest5.jpg" />
                                                    <h3>Creative Ideas</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <img src="<?php echo $this->theme->baseUrl; ?>/example/latest6.jpg" />
                                                    <h3>Creative Ideas</h3>
                                                    <div>
                                                        <a href="<?php echo $this->theme->baseUrl; ?>/example/view.jpg" class="p-view" data-rel="prettyPhoto"></a>
                                                        <a href="blog-single.html" class="p-link"></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $('#latest-work').elastislide({
                                                    imageW  : 235,
                                                    margin  : 10
                                                });
                                            });
                                        </script>
                                    </div>

                                    <div class="short-text">
                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- sidebar -->
                    <aside id="sidebar" class="alignright span4">
                        <!-- Search -->
                        <section class="search clearfix">
                            <form id="search" class="input-append" />
                                <input class="" id="appendedInputButton" size="16" type="text" value="Search..." name="search site" onfocus="if(this.value=='Search...') this.value=''" onblur="if(this.value=='') this.value='Search...'" />
                                <input class="btn search-bt" type="submit" name="submit" value="" />
                            </form>
                        </section>
                        <!-- Tabs -->
                        <section class="block-grey">
                            <!-- Tabs navigation -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#blog" data-toggle="tab">Recent</a></li>
                                <li><a href="#archives" data-toggle="tab">Archives</a></li>
                                <li><a href="#comments" data-toggle="tab">Comments</a></li>
                            </ul>
                            <!-- Tabs content -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="blog">
                                    <section class="post-widget">
                                        <ul class="clearfix">
                                            <li>
                                                <div class="avatar">
                                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar1.jpg" alt="photo" /></a>
                                                </div>
                                                <div class="description">
                                                    <p><a href="blog-single.html">Etiam sagittis purus quis neque pharetra pretium tempor</a></p>
                                                    <span class="date"><em>12 Apr 2012, 3 comments</em></span>
                                                </div>
                                                <div class="clear"></div>
                                            </li>
                                            <li>
                                                <div class="avatar">
                                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar2.jpg" alt="photo" /></a>
                                                </div>
                                                <div class="description">
                                                    <p><a href="blog-single.html">Maecenas malesuada convallis varius. Duis nec luctus leo nam venenatis</a></p>
                                                    <span class="date"><em>12 Apr 2012, 3 comments</em></span>
                                                </div>
                                                <div class="clear"></div>
                                            </li>
                                            <li>
                                                <div class="avatar">
                                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar3.jpg" alt="photo" /></a>
                                                </div>
                                                <div class="description">
                                                    <p><a href="blog-single.html">Donec feugiat luctus sem malesuada sodales praesent rutrum enim eget</a></p>
                                                    <span class="date"><em>12 Apr 2012, 3 comments</em></span>
                                                </div>
                                                <div class="clear"></div>
                                            </li>
                                        </ul>
                                    </section>
                                </div>
                                <div class="tab-pane" id="archives">
                                    <section class="blog-category">
                                        <ul class="ul-col1 clearfix">
                                            <li><a href="#">February 2012</a></li>
                                            <li><a href="#">March 2012</a></li>
                                            <li><a href="#">April 2012</a></li>
                                            <li><a href="#">May 2012</a></li>
                                            <li><a href="#">June 2012</a></li>
                                            <li><a href="#">August 2012</a></li>
                                        </ul>
                                    </section>
                                </div>
                                <div class="tab-pane" id="comments">
                                    <section class="recent-comments">
                                        <ul class="clearfix">
                                            <li><a href="#">Admin</a> on <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                                            <li><a href="#">ShmitAcc</a> on <a href="#">Lorem Ipsum Sed Ut</a></li>
                                            <li><a href="#">Admin</a> on <a href="#">Lorem Ipsum Sed Ut</a></li>
                                            <li><a href="#">Admin</a> on <a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                                            <li><a href="#">ShmitAcc</a> on <a href="#">Lorem Ipsum Sed Ut</a></li>
                                        </ul>
                                    </section>
                                </div>
                            </div>
                        </section>
                        <!-- Advertisment -->
                        <div class="title-divider">
                            <h3>Advertisment</h3>
                            <div class="divider-arrow"></div>
                        </div>
                        <section class="block-dark">
                            <img src="<?php echo $this->theme->baseUrl; ?>/images/adv.gif" alt="" />
                        </section>
                        <!-- Recent Posts -->
                        <div class="title-divider">
                            <h3>Recent Posts</h3>
                            <div class="divider-arrow"></div>
                        </div>
                        <section class="post-widget block-grey">
                            <ul class="clearfix block-light wrap15">
                                <li>
                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar1.jpg" alt="photo" /></a>
                                    <a href="#">Ut in lacus rhoncus elit egesta sluctus. Nullam at</a>
                                    <p><em>12 Apr 2012, 3 comments</em></p>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar2.jpg" alt="photo" /></a>
                                    <a href="#">Ut in lacus rhoncus elit egesta sluctus. Nullam at</a>
                                    <p><em>12 Apr 2012, 3 comments</em></p>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a href="#"><img src="<?php echo $this->theme->baseUrl; ?>/example/sidebar3.jpg" alt="photo" /></a>
                                    <a href="#">Ut in lacus rhoncus elit egesta sluctus. Nullam at</a>
                                    <p><em>12 Apr 2012, 3 comments</em></p>
                                    <div class="clear"></div>
                                </li>
                            </ul>
                        </section>
                    </aside>
                </div>
            </div>
        </div>