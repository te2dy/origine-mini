<?php
/**
 * Origine Mini, a minimal theme for Dotclear.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 */

namespace themes\origine_mini;

use dcCore, html, context;

if (!defined('DC_RC_PATH')) {
    return;
}

\l10n::set(__DIR__ . '/locales/' . dcCore::app()->lang . '/main');

dcCore::app()->addBehavior('publicHeadContent', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniHeadMeta']);
dcCore::app()->addBehavior('publicHeadContent', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniSocialMarkups']);
dcCore::app()->addBehavior('publicEntryBeforeContent', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniPostIntro']);

dcCore::app()->tpl->addValue('origineConfigActive', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineConfigActive']);
dcCore::app()->tpl->addValue('origineMiniStylesInline', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniStylesInline']);
dcCore::app()->tpl->addValue('origineMiniEntryLang', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniEntryLang']);
dcCore::app()->tpl->addValue('origineMiniScreenReaderLinks', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniScreenReaderLinks']);
dcCore::app()->tpl->addValue('origineMiniBlogDescription', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniBlogDescription']);
dcCore::app()->tpl->addValue('origineMiniPostListType', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniPostListType']);
dcCore::app()->tpl->addValue('origineMiniEntryTime', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniEntryTime']);
dcCore::app()->tpl->addValue('origineMiniEntryExcerpt', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniEntryExcerpt']);
dcCore::app()->tpl->addValue('origineMiniPostTagsBefore', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniPostTagsBefore']);
dcCore::app()->tpl->addValue('origineMiniAttachmentTitle', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniAttachmentTitle']);
dcCore::app()->tpl->addValue('origineMiniAttachmentSize', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniAttachmentSize']);
dcCore::app()->tpl->addValue('origineMiniFooterCredits', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniFooterCredits']);
dcCore::app()->tpl->addValue('origineMiniURIRelative', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniURIRelative']);

dcCore::app()->tpl->addBlock('origineMiniPostFooter', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniPostFooter']);
dcCore::app()->tpl->addBlock('origineMiniHeaderIdentity', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniHeaderIdentity']);
dcCore::app()->tpl->addBlock('origineMiniCommentFeedLink', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniCommentFeedLink']);
dcCore::app()->tpl->addBlock('origineMiniWidgetsNav', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniWidgetsNav']);
dcCore::app()->tpl->addBlock('origineMiniWidgetSearchForm', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniWidgetSearchForm']);
dcCore::app()->tpl->addBlock('origineMiniWidgetsExtra', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniWidgetsExtra']);
dcCore::app()->tpl->addBlock('origineMiniFooter', [__NAMESPACE__ . '\tplOrigineMiniTheme', 'origineMiniFooter']);

class tplOrigineMiniTheme
{
    /**
     * Adds meta tags in the <head> section depending on the blog settings.
     *
     * @return void The head meta.
     */
    public static function origineMiniHeadMeta()
    {
        // Adds the name of the editor.
        if (dcCore::app()->blog->settings->system->editor) {
            $editor = dcCore::app()->blog->settings->system->editor;

            // Adds quotes if the value contains one or more spaces.
            $editor = strpos($editor, ' ') === false ? $editor : '"' . $editor . '"';

            echo '<meta name=author content=', $editor, '>', "\n";
        }

        // Adds the content of the copyright notice.
        if (dcCore::app()->blog->settings->system->copyright_notice) {
            $notice = dcCore::app()->blog->settings->system->copyright_notice;

            // Adds quotes if the value contains one or more spaces.
            $notice = strpos($notice, ' ') === false ? $notice : '"' . $notice . '"';

            echo '<meta name=copyright content=', $notice, '>', "\n";
        }

        // Adds the generator of the blog.
        if (dcCore::app()->blog->settings->originemini->global_meta_generator === true) {
            echo '<meta name=generator content=Dotclear>', "\n";
        }
    }

    /**
     * Displays minimal social markups.
     *
     * @link https://meiert.com/en/blog/minimal-social-markup/
     *
     * @return void The social markups.
     */
    public static function origineMiniSocialMarkups()
    {
        if (dcCore::app()->blog->settings->originemini->global_meta_social === true) {
            $title = '';
            $desc  = '';
            $img   = '';

            // Posts and pages.
            if (dcCore::app()->url->type === 'post' || dcCore::app()->url->type === 'pages') {
                $title = dcCore::app()->ctx->posts->post_title;
                $desc  = dcCore::app()->ctx->posts->getExcerpt();

                if ($desc === '') {
                    $desc = dcCore::app()->ctx->posts->getContent();
                }

                $desc = html::decodeEntities(html::clean($desc));
                $desc = preg_replace('/\s+/', ' ', $desc);
                $desc = html::escapeHTML($desc);

                if (strlen($desc) > 180) {
                    $desc = text::cutString($desc, 179) . '…';
                }

                if (context::EntryFirstImageHelper('o', true, '', true)) {
                    $img = context::EntryFirstImageHelper('o', true, '', true);
                }

            // Home.
            } elseif (dcCore::app()->url->type === 'default' || dcCore::app()->url->type === 'default-page') {
                $title = dcCore::app()->blog->name;

                if (intval(context::PaginationPosition()) > 1 ) {
                    $desc = sprintf(
                        __('meta-social-page-with-number'),
                        context::PaginationPosition()
                    );
                }

                if (dcCore::app()->blog->desc !== '') {
                    if ($desc !== '') {
                        $desc .= ' – ';
                    }

                    $desc .= dcCore::app()->blog->desc;
                    $desc  = html::decodeEntities(html::clean($desc));
                    $desc  = preg_replace('/\s+/', ' ', $desc);
                    $desc  = html::escapeHTML($desc);

                    if (strlen($desc) > 180) {
                        $desc = text::cutString($desc, 179) . '…';
                    }
                }

            // Categories.
            } elseif (dcCore::app()->url->type === 'category') {
                $title = dcCore::app()->ctx->categories->cat_title;

                if (dcCore::app()->ctx->categories->cat_desc !== '') {
                    $desc = dcCore::app()->ctx->categories->cat_desc;
                    $desc = html::decodeEntities(html::clean($desc));
                    $desc = preg_replace('/\s+/', ' ', $desc);
                    $desc = html::escapeHTML($desc);

                    if (strlen($desc) > 180) {
                        $desc = text::cutString($desc, 179) . '…';
                    }
                }

            // Tags.
            } elseif (dcCore::app()->url->type === 'tag' && dcCore::app()->ctx->meta->meta_type === 'tag') {
                $title = dcCore::app()->ctx->meta->meta_id;
                $desc    = sprintf(__('meta-social-tags-post-related'), $title);
            }

            $title = html::escapeHTML($title);
            $img   = html::escapeURL($img);

            if ($title) {
                if ($img) {
                    echo '<meta name=twitter:card content=summary_large_image>', "\n";
                }

                echo '<meta property=og:title content="', $title, '">', "\n";

                if ($desc) {
                    echo '<meta property="og:description" name="description" content="', $desc, '">', "\n";
                }

                if ($img) {
                    echo '<meta property="og:image" content="', $img, '">', "\n";
                }
            }
        }
    }

    /**
     * Displays the excerpt as an introduction before post content.
     */
    public static function origineMiniPostIntro()
    {
        //if (dcCore::app()->blog->settings->originemini->content_post_intro === true && dcCore::app()->ctx->posts->post_excerpt) {
            echo '<div id=post-single-excerpt>' . dcCore::app()->ctx->posts->getExcerpt() . '</div>';
        //}
    }

    /**
     * Displays wide images.
     *
     * If the image width is wider than the page width, the image will overflow to be displayed bigger.
     *
     * @return void
     */
    public static function origineMiniImagesWide()
    {
        if (dcCore::app()->blog->settings->origineConfig->active === true && dcCore::app()->blog->settings->origineConfig->content_images_wide === true && (dcCore::app()->url->type === 'post' || dcCore::app()->url->type === 'pages')) {
                $page_width_em = dcCore::app()->blog->settings->origineConfig->global_page_width ? dcCore::app()->blog->settings->origineConfig->global_page_width : 30;
                ?>
                <script>window.addEventListener("load",imageWide);window.addEventListener("resize",imageWide);function getMeta(url,callback){var img=new Image();img.src=url;img.addEventListener("load",function(){callback(this.width,this.height)})}
function imageWide(){var pageWidthEm=parseInt(<?php echo $page_width_em; ?>),imgWideWidthEm=pageWidthEm+10,pageWidthPx=0,imgWideWidthPx=0,fontSizePx=0;const element=document.createElement('div');element.style.width='1rem';element.style.display='none';document.body.append(element);const widthMatch=window.getComputedStyle(element).getPropertyValue('width').match(/\d+/);element.remove();if(widthMatch&&widthMatch.length>=1){fontSizePx=parseInt(widthMatch[0])}
if(fontSizePx>0){pageWidthPx=pageWidthEm*fontSizePx;imgWideWidthPx=imgWideWidthEm*fontSizePx}
var img=document.getElementsByTagName("article")[0].getElementsByTagName("img"),i=0;while(i<img.length){let myImg=img[i];getMeta(myImg.src,function(width,height){let imgWidth=width,imgHeight=height,myImgStyle="";if(imgWidth>pageWidthPx&&imgWidth>imgHeight){if(imgWidth>imgWideWidthPx){imgHeight=parseInt(imgWideWidthPx*imgHeight/imgWidth);imgWidth=imgWideWidthPx}
myImgStyle="display:block;margin-left:50%;transform:translateX(-50%);max-width:95vw;";myImg.setAttribute("style",myImgStyle);if(imgWidth){myImg.setAttribute("width",imgWidth)}
if(imgHeight){myImg.setAttribute("height",imgHeight)}}});i++}}</script>
            <?php
        }
    }

    /**
     * Checks if the plugin origineConfig is installed and activated.
     *
     * To support the user's settings, the version of the plugin must be superior or equal to 2.1.
     *
     * @return bool Returns true if the plugin is installed and activated.
     */
    public static function origineConfigActive()
    {
        if (dcCore::app()->plugins->moduleExists('origineConfig') === true && version_compare('2.1', dcCore::app()->plugins->moduleInfo('origineConfig', 'version'), '<=') && dcCore::app()->blog->settings->origineConfig->active === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds styles in the head.
     *
     * If origineConfig is activated, it will return custom styles generated by the plugin.
     *
     * @return string The styles.
     */
    public static function origineMiniStylesInline()
    {
        if (dcCore::app()->blog->settings->originemini->styles) {
            return '<style>' . dcCore::app()->blog->settings->originemini->styles . '</style>';
        }
    }

    /**
     * Displays a lang attribute and its value when the language of the current post is different
     * from the language defined for the whole blog.
     *
     * @return void The lang attribute.
     */
    public static function origineMiniEntryLang()
    {
        return '<?php
            if (dcCore::app()->ctx->posts->post_lang !== dcCore::app()->blog->settings->system->lang) {
                echo " lang=", dcCore::app()->ctx->posts->post_lang;
            }
        ?>';
    }

    /**
     * Displays navigation links for screen readers.
     *
     * @return void The navigation links.
     */
    public static function origineMiniScreenReaderLinks()
    {
        $links = '<a id=skip-content class=skip-links href=#site-content>' . __('skip-link-content') . '</a>';

        // If simpleMenu exists, is activated and a menu has been set, then adds a link to it.
        if (dcCore::app()->plugins->moduleExists('simpleMenu') && dcCore::app()->blog->settings->system->simpleMenu_active === true) {
          $links .= '<a id=skip-menu class=skip-links href=#main-menu>' . __('skip-link-menu') . '</a>';
        }

        $plugin_activated = self::origineConfigActive();

        // Adds a link to the footer, except if it has been disabled in origineConfig.
        if ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->footer_enabled === true) {
          $links .= '<a id=skip-menu class=skip-links href=#site-footer>' . __('skip-link-footer') . '</a>';
        }

        return $links;
    }

    /**
     * Displays the blog description.
     *
     * @return void The blog description.
     */
    public static function origineMiniBlogDescription()
    {
        if (dcCore::app()->blog->desc && dcCore::app()->blog->settings->originemini->header_description === true) {
            $description = html::decodeEntities(html::clean(dcCore::app()->blog->desc));
            $description = preg_replace('/\s+/', ' ', $description);
            $description = html::escapeHTML($description);

            if ($description !== '') {
                return '<h2 class=text-secondary id=site-description>' . $description . '</h2>';
            }
        }
    }

    /**
     * Credits to display at the bottom of the site.
     *
     * They can be removed with the plugin origineConfig.
     * Dotclear and theme versions are shown only on dev environments.
     *
     * @return void The footer credits.
     */
    public static function origineMiniFooterCredits()
    {
        $plugin_activated = self::origineConfigActive();

        if ($plugin_activated === false || ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->footer_credits === true)) {
            if (!defined('DC_DEV') || (defined('DC_DEV') && DC_DEV === false)) {
                return '<div class=site-footer-block>' . __('footer-powered-by') . '</div>';
            } else {
                $dc_version       = dcCore::app()->getVersion('core');
                $dc_version_parts = explode('-', $dc_version);
                $dc_version_short = $dc_version_parts[0] ? $dc_version_parts[0] : $dc_version;

                $theme_version = dcCore::app()->themes->moduleInfo('origine-mini', 'version');

                return '<div class=site-footer-block>' . sprintf(__('footer-powered-by-dev'), $dc_version, $dc_version_short, $theme_version) . '</div>';
            }
        }
    }

    /**
     * Loads the right entry-list template based on origineConfig settings.
     * Default: short
     *
     * @return void The entry-list template.
     */
    public static function origineMiniPostListType()
    {
        $post_list_types = ['short', 'extended'];

        if (!in_array(dcCore::app()->blog->settings->originemini->content_post_list_type, $post_list_types) || dcCore::app()->blog->settings->originemini->content_post_list_type === 'short') {
            return dcCore::app()->tpl->includeFile(['src' => '_entry-list-short.html']);
        } else {
            return dcCore::app()->tpl->includeFile(['src' => '_entry-list-extended.html']);
        }
    }

    /**
     * Displays the published time of posts in the post list.
     *
     * @param array $attr Attributes to customize the value.
     *                    Attribute allowed: context
     *                    Values allowed:
     *                    - (string) post-list
     *                    - (string) post
     *
     * @return void The published time of the post.
     */
    public static function origineMiniEntryTime($attr)
    {
        if ( !empty($attr['context']) && (dcCore::app()->blog->settings->originemini->content_post_list_time === true && $attr['context'] === 'post-list') || (dcCore::app()->blog->settings->originemini->content_post_time === true && $attr['context'] === 'post')) {
            $separator = dcCore::app()->blog->settings->originemini->content_separator ? dcCore::app()->blog->settings->originemini->content_separator : '|';

            return ' <?php
                echo "' . $separator . '", " ", dcCore::app()->ctx->posts->getDate("' . dcCore::app()->blog->settings->system->time_format . '");
            ?>';
       }
    }

    /**
     * Returns an excerpt of the post for the entry-list-extended template.
     *
     * Gets the excerpt defined by the author or, if it does not exists, an excerpt from the content.
     *
     * @param array $attr Modifying attributes.
     *
     * @return void The entry excerpt.
     */
    public static function origineMiniEntryExcerpt($attr)
    {
        return '<?php
            $the_excerpt = "";

            if (' . sprintf(dcCore::app()->tpl->getFilters($attr), 'dcCore::app()->ctx->posts->getExcerpt()') . ' !== "") {
                $the_excerpt = ' . sprintf(dcCore::app()->tpl->getFilters($attr), 'dcCore::app()->ctx->posts->getExcerpt()') . ';
            } else {
                $the_excerpt = ' . sprintf(dcCore::app()->tpl->getFilters($attr), 'dcCore::app()->ctx->posts->getContent()') . ';

                if (strlen($the_excerpt) > 200) {
                    $the_excerpt  = substr($the_excerpt, 0, 200);
                    $the_excerpt  = preg_replace("/[^a-z0-9]+\Z/i", "", $the_excerpt);
                    $the_excerpt .= "…";
                }
            }

            if ($the_excerpt !== "") {
                if (dcCore::app()->ctx->posts->post_lang === dcCore::app()->blog->settings->system->lang) {
                    $lang = "";
                } else {
                    $lang = " lang=" . dcCore::app()->ctx->posts->post_lang;
                }

                echo "<p class=\"post-excerpt text-secondary\"" . $lang . ">",
                     $the_excerpt,
                     " <a aria-label=\"", sprintf(__("post-list-open-aria"), dcCore::app()->ctx->posts->post_title), "\" href=\"", dcCore::app()->ctx->posts->getURL(), "\">" . __("post-list-open"), "</a>",
                     "</p>";
            }
        ?>';
    }

    /**
     * Adds a text string before the tag list of posts.
     *
     * @return void The text string.
     */
    public static function origineMiniPostTagsBefore()
    {
        return '<?php
            if (dcCore::app()->ctx->posts->post_meta) {
                $post_meta = unserialize(dcCore::app()->ctx->posts->post_meta);

                if (is_array($post_meta) === true && isset($post_meta["tag"]) === true) {
                    if (count($post_meta["tag"]) > 1) {
                        echo __("post-tags-prefix-multiple");
                    } elseif (count($post_meta["tag"]) === 1) {
                        echo __("post-tags-prefix-one");
                    }
                }
            }
        ?>';
    }

    /**
     * Adds a title in the plural or singular at the top of post attachment list.
     *
     * @return void The attachment area title.
     */
    public static function origineMiniAttachmentTitle()
    {
        return '<?php
            if (count(dcCore::app()->ctx->attachments) === 1) {
                echo __("attachments-title-one");
            } else {
                echo __("attachments-title-multiple");
            }
        ?>';
    }

    /**
     * Displays the attachment size.
     *
     * Based on Clearbricks package, Common subpackage and files class.
     *
     * @return void The attachment size.
     */
    public static function origineMiniAttachmentSize()
    {
        return '<?php
            $kb = 1024;
            $mb = 1024 * $kb;
            $gb = 1024 * $mb;
            $tb = 1024 * $gb;

            $size = $attach_f->size;

            // Setting ignored for some reason:
            // setlocale(LC_ALL, "fr_FR");

            if (dcCore::app()->blog->settings->system->lang === "fr") {
                $locale_decimal = ",";
            } else {
                $lang_conv      = localeconv();
                $locale_decimal = $lang_conv["decimal_point"];
            }

            if ($size > 0) {
                if ($size < $kb) {
                    printf(__("attachment-size-b"), $size);
                } elseif ($size < $mb) {
                    printf(__("attachment-size-kb"), number_format($size / $kb, 1, $locale_decimal));
                } elseif ($size < $gb) {
                    printf(__("attachment-size-mb"), number_format($size / $mb, 1, $locale_decimal));
                } elseif ($size < $tb) {
                    printf(__("attachment-size-gb"), number_format($size / $gb, 1, $locale_decimal));
                } else {
                    printf(__("attachment-size-tb"), number_format($size / $tb, 1, $locale_decimal));
                }
            }
        ?>';
    }

    /**
     * Returns the relative URI of the current page.
     *
     * @return void The relative URI.
     */
    public static function origineMiniURIRelative()
    {
        return '<?php echo filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL); ?>';
    }

    /**
     * Displays the footer of the post if it has content.
     *
     * @param array $attr    Unused.
     * @param void  $content The post footer.
     *
     * @return void The post footer.
     */
    public static function origineMiniPostFooter($attr, $content)
    {
        $has_attachment = false;

        if (dcCore::app()->ctx->posts->countMedia('attachment') > 0) {
            $has_attachment = true;
        }

        $has_category = false;

        if (dcCore::app()->ctx->posts->cat_id) {
            $has_category = true;
        }

        $has_tag = false;

        if (dcCore::app()->ctx->posts->post_meta) {
            $post_meta = unserialize(dcCore::app()->ctx->posts->post_meta);

            if (is_array($post_meta) === true && isset($post_meta['tag']) === true && count($post_meta['tag']) > 0) {
                $has_tag = true;
            }
        }

        if ($has_attachment === true || $has_category === true || $has_tag === true) {
            return $content;
        }
    }

    /**
     * Displays the header site title and description if the description is shown.
     *
     * @param array $attr    Unused.
     * @param void  $content The header.
     *
     * @return void The link.
     */
    public static function origineMiniHeaderIdentity($attr, $content)
    {
        $plugin_activated = self::origineConfigActive();

        if (dcCore::app()->blog->settings->originemini->header_description !== true) {
            return $content;
        } else {
            return '<div id=site-identity>' . $content . '</div>';
        }

    }

    /**
     * Displays a link to the comment feed.
     *
     * @param array $attr    Unused.
     * @param void  $content The link.
     *
     * @return void The link.
     */
    public static function origineMiniCommentFeedLink($attr, $content)
    {
        $plugin_activated = self::origineConfigActive();

        if ($plugin_activated === false || ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->content_comment_links === true)) {
            return $content;
        }
    }

    /**
     * Displays navigation widgets.
     *
     * @param array $attr    Unused.
     * @param void  $content The content of the widget area.
     *
     * @return void The navigation widget.
     */
    public static function origineMiniWidgetsNav($attr, $content)
    {
        $plugin_activated = self::origineConfigActive();

        if ($plugin_activated === false || ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->widgets_nav_position !== 'disabled')) {
            return $content;
        }
    }

    /**
     * Displays a search form before the navigation widget area.
     *
     * @param array $attr    Unused.
     * @param void  $content The content of the search form.
     *
     * @return void The search form.
     */
    public static function origineMiniWidgetSearchForm($attr, $content)
    {
        $plugin_activated = self::origineConfigActive();

        if ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->widgets_search_form === true && dcCore::app()->url->type !== 'search') {
            return $content;
        }
    }

    /**
     * Displays extra widgets.
     *
     * @param array $attr    Unused.
     * @param void  $content The content of the widget area.
     *
     * @return void The navigation widget.
     */
    public static function origineMiniWidgetsExtra($attr, $content)
    {
        $plugin_activated = self::origineConfigActive();

        if ($plugin_activated === false || ($plugin_activated === true && dcCore::app()->blog->settings->origineConfig->widgets_extra_enabled === true)) {
            return $content;
        }
    }

    /**
     * Displays the footer.
     *
     * @param array $attr    Unused.
     * @param void  $content The content of the footer.
     *
     * @return void The footer.
     */
    public static function origineMiniFooter($attr, $content)
    {
        if (dcCore::app()->blog->settings->originemini->footer_enabled !== false) {
            return $content;
        }
    }
}
