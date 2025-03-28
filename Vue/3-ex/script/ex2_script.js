new Vue({
    el: '#app',
    data() {
        return {
            count:0,
            cnt:0,
            cnt2:0
        };
    },
    methods: {
        add10() {
            this.count+=10;
            this.cnt++;
        },
        add100(){
            this.count+=100;
            this.cnt2++;
        },
        clear(){
            this.count=0;
            this.cnt=0;
            this.cnt2=0;
        }
    }
});

