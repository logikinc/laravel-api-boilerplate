<template>
    <div class="container">
        <div class="row">
            <login-form></login-form>
        </div>
    </div>
</template>

<script>
    import LoginForm from './../login/LoginForm.vue'

    export default {
        name: 'login',
        components: {
            LoginForm
        }
    }
</script>
