define(['jquery'], function ($) {
    $(document).ready(function () {
        $('#restore-recursively').on('click', function () {
            var restoreRecursively = $(this).prop('checked');
            return restoreRecursively;
        });
    });
});

