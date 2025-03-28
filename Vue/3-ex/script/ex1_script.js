new Vue({
    el: '#app',
    data() {
        return {
            count:1
        };
    },
    methods: {
        multiply() {
            this.count*=2;
        },
        divide(){
            this.count/=2;
        }
    }
});