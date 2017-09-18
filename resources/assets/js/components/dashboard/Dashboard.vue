<template>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                You are logged in
            </div>
        </div>
    </div>
</template>

<style scoped>
    .full-height {
        height: 80vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .m-b-md {
        margin-bottom: 30px;
    }
</style>

<script>
    import {siteName} from './../../config';

    export default {
        name: 'dashboard',
        data() {
            return {
                siteName: siteName
            }
        },
        components: {}
    }
</script>
