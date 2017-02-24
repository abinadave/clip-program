<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>
                    <div class="panel-body">
                        <form-grant-person></form-grant-person>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CompFormGrant from './form-grant-person.vue'
    export default {
        mounted() {
            console.log('Component mounted.');
        },
        components: {
            'form-grant-person': CompFormGrant
        }
    }
</script>
