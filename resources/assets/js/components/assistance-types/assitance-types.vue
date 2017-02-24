<template>
    <div class="container">
        <div class="row">
            <form-create-type @newtype="newTypeAdded"></form-create-type>
            <type-list :types="types"></type-list>
        </div>
    </div>
</template>

<script>
    import CompCreateType from './form-create-assistance-type.vue'
    import CompTypes from './list-of-types.vue'
    import toastr from 'toastr'
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetch();
        },
        components: {
            'form-create-type': CompCreateType,
            'type-list': CompTypes
        },
        data(){
            return {
                types: []
            }
        },
        methods: {
            fetch(){
                let self = this;
                self.$http.get('/type').then(self.successFetch, (resp) => {
                    console.log(resp);
                })
            },
            successFetch(resp){
                let self = this;
                if (resp.status === 200) {
                    let json = resp.body;
                    self.types = json.types;
                }
            },
            newTypeAdded(json){
                let self = this;
                self.types.unshift(json);
            }
        }
    }
</script>
