new Vue({
    el: '#app',
    data() {
        return {
            name: 'name',
            email: 'email',
            tel: '00'
           
        };
    },
    computed: {
        isInValidName() {
            return this.name.length < 4;
        },
        isInValidEmail() {
            const regex = new RegExp(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i)
            return !regex.test(this.email);
        },
            isInValidTel() {
                const tel = this.tel;
                const isErr = tel.length < 8 || isNaN(Number(tel));
                return isErr;
            }
        }
});
