$.get('engine/ajax/get_page_content.php?page=' + page, null, function(result) {
    $("#" + target_id).html(result); // Or whatever you need to insert the result
 }, 'html');