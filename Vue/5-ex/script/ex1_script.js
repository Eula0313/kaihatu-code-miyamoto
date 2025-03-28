new Vue({
    el: '#app',
    data() {
        return {
            change: '今日は暖かいです。'
        };
    },
    methods: {
        increment() {
            this.change = '今日は寒いです。';
        }
        
    }
});
