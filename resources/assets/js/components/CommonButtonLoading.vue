<script>
    export default {
        bind: (el, binding, vnode) => {
            var ele = $(el).find('i').first();
            ele.attr('saved-class', ele.attr('class'));
        },
        update: (el, binding, vnode) => {
            if (binding.oldValue != binding.value) {
                var ele = $(el).find('i').first();
                if (binding.value) {
                    ele.attr('class', 'fa fa-fw fa-rotate-right fa-spin');
                } else {
                    ele.attr('class', ele.attr('saved-class'));
                }
            }
        }
    }
</script>