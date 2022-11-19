/**
 * Shows or hides settings depending on others.
 */

function disableInputs() {
    if (document.getElementById("content_post_list_time").checked || document.getElementById("content_post_time").checked) {
        document.getElementById("content_separator-input").style.display = "block";
        document.getElementById("content_separator-description").style.display = "block";
    } else {
        document.getElementById("content_separator-input").style.display = "none";
        document.getElementById("content_separator-description").style.display = "none";
    }

    if (document.getElementById("widgets_nav_position").value === 'disabled') {
        document.getElementById("widgets_search_form-input").style.display = "none";
        document.getElementById("widgets_search_form-description").style.display = "none";
    } else {
        document.getElementById("widgets_search_form-input").style.display = "block";
        document.getElementById("widgets_search_form-description").style.display = "block";
    }

    if (!document.getElementById("footer_enabled").checked) {
        document.getElementById("footer_credits-input").style.display               = "none";
        document.getElementById("footer_credits-description").style.display         = "none";
        document.getElementById("sub-section-social-links").style.display           = "none";
        document.getElementById("footer_social_links_diaspora-input").style.display = "none";
        document.getElementById("footer_social_links_discord-input").style.display  = "none";
        document.getElementById("footer_social_links_facebook-input").style.display = "none";
        document.getElementById("footer_social_links_github-input").style.display   = "none";
        document.getElementById("footer_social_links_mastodon-input").style.display = "none";
        document.getElementById("footer_social_links_signal-input").style.display   = "none";
        document.getElementById("footer_social_links_tiktok-input").style.display   = "none";
        document.getElementById("footer_social_links_twitter-input").style.display  = "none";
        document.getElementById("footer_social_links_whatsapp-input").style.display = "none";
    } else {
        document.getElementById("footer_credits-input").style.display               = "block";
        document.getElementById("footer_credits-description").style.display         = "block";
        document.getElementById("sub-section-social-links").style.display           = "block";
        document.getElementById("footer_social_links_diaspora-input").style.display = "block";
        document.getElementById("footer_social_links_discord-input").style.display  = "block";
        document.getElementById("footer_social_links_facebook-input").style.display = "block";
        document.getElementById("footer_social_links_github-input").style.display   = "block";
        document.getElementById("footer_social_links_mastodon-input").style.display = "block";
        document.getElementById("footer_social_links_signal-input").style.display   = "block";
        document.getElementById("footer_social_links_tiktok-input").style.display   = "block";
        document.getElementById("footer_social_links_twitter-input").style.display  = "block";
        document.getElementById("footer_social_links_whatsapp-input").style.display = "block";
    }
}

window.addEventListener("onload", disableInputs);
window.addEventListener("onchange", disableInputs);
