<?php
/**
 * The template for displaying comments
 *
 * @package McMaster_WC_Theme
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h3 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'mcmaster-wc-theme'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    esc_html(_nx('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'mcmaster-wc-theme')),
                    number_format_i18n($comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h3>

        <?php the_comments_navigation(); ?>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'style'      => 'ul',
                'short_ping' => true,
                'callback'   => 'mcmaster_comment_callback',
            ));
            ?>
        </ul>

        <?php
        the_comments_navigation();

        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'mcmaster-wc-theme'); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    // Comment form
    $fields = array(
        'author' => '<p class="comment-form-author">
                        <label for="author">' . esc_html__('Name', 'mcmaster-wc-theme') . ' <span class="required">*</span></label>
                        <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" required />
                     </p>',
        'email'  => '<p class="comment-form-email">
                        <label for="email">' . esc_html__('Email', 'mcmaster-wc-theme') . ' <span class="required">*</span></label>
                        <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required />
                     </p>',
        'url'    => '<p class="comment-form-url">
                        <label for="url">' . esc_html__('Website', 'mcmaster-wc-theme') . '</label>
                        <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />
                     </p>',
    );

    $args = array(
        'fields'               => $fields,
        'comment_field'        => '<p class="comment-form-comment">
                                    <label for="comment">' . esc_html__('Comment', 'mcmaster-wc-theme') . ' <span class="required">*</span></label>
                                    <textarea id="comment" name="comment" cols="45" rows="8" required></textarea>
                                  </p>',
        'title_reply'          => esc_html__('Leave a Comment', 'mcmaster-wc-theme'),
        'title_reply_to'       => esc_html__('Leave a Reply to %s', 'mcmaster-wc-theme'),
        'cancel_reply_link'    => esc_html__('Cancel Reply', 'mcmaster-wc-theme'),
        'label_submit'         => esc_html__('Post Comment', 'mcmaster-wc-theme'),
        'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
        'comment_notes_before' => '<p class="comment-notes">' . esc_html__('Your email address will not be published. Required fields are marked *', 'mcmaster-wc-theme') . '</p>',
        'class_form'           => 'comment-form',
        'class_submit'         => 'submit-button',
    );

    comment_form($args);
    ?>

</div>

<style>
/* Comments Styles */
.comments-area {
    margin-top: 50px;
    padding: 40px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.comments-title {
    font-size: 24px;
    margin-bottom: 30px;
    color: #333;
    border-bottom: 2px solid #0066cc;
    padding-bottom: 15px;
}

.comment-list {
    list-style: none;
    padding: 0;
    margin: 0 0 40px 0;
}

.comment-list .comment {
    padding: 25px 0;
    border-bottom: 1px solid #eee;
}

.comment-list .children {
    margin-left: 40px;
    list-style: none;
}

.comment-meta {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    gap: 15px;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 10px;
}

.comment-author .avatar {
    border-radius: 50%;
}

.comment-author .fn {
    font-weight: 600;
    color: #333;
    text-decoration: none;
}

.comment-metadata {
    font-size: 14px;
    color: #666;
}

.comment-metadata a {
    color: #666;
    text-decoration: none;
}

.comment-metadata a:hover {
    color: #0066cc;
}

.comment-content {
    margin-bottom: 15px;
    line-height: 1.6;
}

.comment-content p {
    margin-bottom: 15px;
}

.reply {
    font-size: 14px;
}

.reply a {
    color: #0066cc;
    text-decoration: none;
    padding: 5px 15px;
    border: 1px solid #0066cc;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.reply a:hover {
    background: #0066cc;
    color: white;
}

.comment-respond {
    margin-top: 40px;
    padding-top: 40px;
    border-top: 2px solid #f0f0f0;
}

.comment-reply-title {
    font-size: 22px;
    margin-bottom: 25px;
    color: #333;
}

.comment-form {
    display: grid;
    gap: 20px;
}

.comment-form-author,
.comment-form-email,
.comment-form-url {
    margin: 0;
}

.comment-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.comment-form .required {
    color: #e74c3c;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.comment-form input[type="text"]:focus,
.comment-form input[type="email"]:focus,
.comment-form input[type="url"]:focus,
.comment-form textarea:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 2px rgba(0,102,204,0.2);
}

.comment-form textarea {
    resize: vertical;
    min-height: 120px;
}

.comment-notes {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

.form-submit {
    margin: 0;
}

.submit-button {
    background: #0066cc;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.submit-button:hover {
    background: #0052a3;
}

.no-comments {
    text-align: center;
    padding: 30px;
    color: #666;
    font-style: italic;
}

.comment-navigation {
    margin: 30px 0;
    text-align: center;
}

.comment-navigation .nav-links {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comment-navigation a {
    color: #0066cc;
    text-decoration: none;
    padding: 8px 16px;
    border: 1px solid #0066cc;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.comment-navigation a:hover {
    background: #0066cc;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .comments-area {
        padding: 25px 20px;
    }
    
    .comment-list .children {
        margin-left: 20px;
    }
    
    .comment-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .comment-navigation .nav-links {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<?php
/**
 * Custom comment callback function
 */
if (!function_exists('mcmaster_comment_callback')) {
    function mcmaster_comment_callback($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <div class="comment-body">
                <div class="comment-meta">
                    <div class="comment-author vcard">
                        <?php echo get_avatar($comment, 50); ?>
                        <cite class="fn"><?php comment_author_link(); ?></cite>
                    </div>
                    <div class="comment-metadata">
                        <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php comment_date(); ?> <?php esc_html_e('at', 'mcmaster-wc-theme'); ?> <?php comment_time(); ?>
                            </time>
                        </a>
                        <?php edit_comment_link(__('Edit', 'mcmaster-wc-theme'), '  ', ''); ?>
                    </div>
                </div>

                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'mcmaster-wc-theme'); ?></p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="reply">
                    <?php
                    comment_reply_link(array_merge($args, array(
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => __('Reply', 'mcmaster-wc-theme'),
                    )));
                    ?>
                </div>
            </div>
        <?php
    }
}
?>