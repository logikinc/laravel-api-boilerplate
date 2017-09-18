<template>
    <div class="col-sm-6 col-md-4 col-md-offset-4">
        <div class="account-wall">
            <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                 alt="">
            <form class="form-signin" @submit.prevent="login()">
                <div class="form-group" :class="{ 'has-error' : errors.email}">
                    <label class="control-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" v-model="email">
                    <span class="help-block" v-if="errors.email">{{ errors.email }}</span>
                </div>
                <div class="form-group" :class="{ 'has-error' : errors.password}">
                    <label class="control-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" v-model="password">
                    <span class="help-block" v-if="errors.password">{{ errors.password }}</span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
                <a href="#" class="pull-right need-help">Forgot Password ?</a><span class="clearfix"></span>
            </form>
        </div>
    </div>
</template>

<style scoped>

    .form-signin
    {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin
    {
        margin-bottom: 10px;
    }
    .form-signin .checkbox
    {
        font-weight: normal;
    }
    .form-signin .form-control
    {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .form-signin .form-control:focus
    {
        z-index: 2;
    }
    .form-signin input[type="text"]
    {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .form-signin input[type="password"]
    {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .account-wall
    {
        margin-top: 20px;
        padding: 40px 0px 20px 0px;
        background-color: #f7f7f7;
        -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    }
    .profile-img
    {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
    .need-help
    {
        margin-top: 10px;
    }


</style>

<script>
    import {mapState} from 'vuex';

    export default {
        name: 'login-form',
        created() {
            this.$store.dispatch('clearLoginErrors');
        },
        data() {
            return {
                email: null,
                password: null
            }
        },
        computed: mapState({
                errors: state => state.login.errors
            }),

        methods: {
            login() {
                const loginData = {
                    email: this.email,
                    password: this.password
                };

                this.$store.dispatch('loginRequest', loginData)
                    .then((response) => {
                        this.$router.push({name: 'dashboard'});
                    })
                    .catch((error) => {});
            }
        }
    }
</script>
