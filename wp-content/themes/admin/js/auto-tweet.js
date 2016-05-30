jQuery(document).ready(function(){
    var countDiv = jQuery('#admin-auto-tweet-count');
    var tweetInput = jQuery('#admin-auto-tweet-text');

    function updateTweetCount() {
        var tweet = jQuery(tweetInput).val();
        var tweetCount = tweet.length;
        jQuery(countDiv).text(tweetCount);

        if(tweetCount > 140) {
            jQuery(countDiv).css('color', 'red');
        } else {
            jQuery(countDiv).css('color', 'black');
        }
    }

    if(countDiv.length && tweetInput.length) {
        jQuery(tweetInput).change(function() {
            updateTweetCount();
        });

        jQuery(tweetInput).keyup(function(){
            updateTweetCount();
        });
    }
});