new Vue({
    el: '#app',
    data() {
        return {
           isActive: false
        };
    },
    methods: {
        toggleButton() {
            this.isActive = ! this.isActive;
        }
    }
});
