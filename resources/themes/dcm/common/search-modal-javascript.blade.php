<script>
    $(document).ready(function(){
        var openCtrl = document.getElementById('btn-search'),
            closeCtrl = document.getElementById('btn-search-close'),
            searchContainer = document.querySelector('.search'),
            inputSearch = searchContainer.querySelector('.search__input');

        function init() {
            initEvents();
        }

        function initEvents() {
            openCtrl.addEventListener('click', openSearch);
            closeCtrl.addEventListener('click', closeSearch);
            document.addEventListener('keyup', function(ev) {
                // escape key.
                if( ev.keyCode == 27 ) {
                    closeSearch();
                }
            });
        }

        function openSearch() {
            searchContainer.classList.add('dcm-search--open');
            inputSearch.focus();
        }

        function closeSearch() {
            searchContainer.classList.remove('dcm-search--open');
            inputSearch.blur();
            inputSearch.value = '';
        }

        init();
    });
</script>