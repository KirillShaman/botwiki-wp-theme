"use strict";

var helpers = {
  wait_for_el: function(selector, callback) {
    if (jQuery(selector).length) {
      callback();
    } else {
      var helpers = this;

      setTimeout(function() {
        helpers.wait_for_el(selector, callback);
      }, 100);
    }
  },
  hide_duplicate_posts_menu: function(){
    var helpers = this;

    this.wait_for_el('[id="menu-posts"]', function() {
      /* Quick fix for duplicate Posts appearing in admin, after modifying default post slug. */
      var $menu_posts = $('[id="menu-posts"]');

      if ($menu_posts.length > 1){
        $menu_posts.first().hide();  
      }
    });
  }
}

export {helpers};
