<template>
    <div class="">
       <form @submit.prevent="submitForm">
        <div class="form-group col-md-5">
            <label>Province</label>
            <select v-model="province" class="form-control">
               <option v-for="province in provinces" :value="province.id">
                 {{ province.name }}
               </option>
            </select>
          </div>
          <div class="form-group col-md-5">
            <label >Type of Assistance</label>
            <select class="form-control">
               <option v-for="type in types" :value="type.id">
                  {{ type.name }}
               </option>
            </select>
          </div>
          <div class="form-group col-md-5">
            <label>FR Name</label>
            <input type="text" class="form-control" placeholder="FR name">
          </div>
          
          <div class="form-group col-md-5">
            <label>Date Submitted</label>
            <input type="date" class="form-control" placeholder="Date submitted">
          </div>
          <div class="form-group col-md-5">
            <label>Amount</label>
            <input type="number" class="form-control" placeholder="Enter value">
          </div>
          <div class="form-group col-md-5">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="Address">
          </div>
          <div class="form-group col-md-5">
            <button type="submit" class="btn btn-primary btn-lg active">Submit</button>
          </div>
          
       </form>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetchProvinces();
            this.fetchTypes();
        },
        data(){
            return {
                types: [], provinces: [],
                province: 1,
                typeOfAssistance: 1,
                frName: 0,
                dateSubmitted: '',
                amount: 0,
                address: '',
                actionTaken: '',
            }
        },
        methods: {
            submitForm(){
                let self = this;
                self.$http.post('/assist',{
                   fr_name: self.frName,
                   type_of_assistance: self.typeOfAssistance,
                   date_submitted: self.dateSubmitted,
                   amount: self.amount,
                   address: self.address,
                   action_taken: self.actionTaken
                }).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        console.log(json)
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            fetchProvinces(){
                let self = this;
                self.$http.get('/province').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.provinces = json.provinces;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            },
            fetchTypes(){
                let self = this;
                self.$http.get('/type').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.types = json.types;
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                      console.log(resp)
                    }
                });
            }
        }
    }
</script>
