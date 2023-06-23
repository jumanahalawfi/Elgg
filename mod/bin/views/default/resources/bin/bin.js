define(['jquery'], function ($) {
    $(document).ready(function () {
        $('#restore-recursively').on('change', function () {
            var restoreRecursively = $(this).prop('checked');
            console.log('Boolean value:', restoreRecursively);
            elgg.ui = elgg.ui || {};
            elgg.ui.restoreRecursively = restoreRecursively;
            $(document).trigger('restoreRecursivelyChanged', [restoreRecursively]);
        });
    });
});

