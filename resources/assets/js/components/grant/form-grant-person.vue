<template>
    <div class="">
       <form @submit.prevent="submitForm">
        <div :class="{ 'form-group col-md-5 ': true }">
            <label>Province</label>
            <select v-model="province" class="form-control">
               <option v-for="province in provinces" :value="province.id">
                 {{ province.name }}
               </option>
            </select>
          </div>
          <div :class="{ 'form-group col-md-5 ': true, 'has-warning' : error.type_of_assistance }">
            <label>Type of Assistance</label>
            <select v-model="typeOfAssistance" class="form-control">
               <option v-for="type in types" :value="type.id">
                  {{ type.name }}
               </option>
            </select>
          </div>
          <div :class="{ 'form-group col-md-5 ': true, 'has-warning' : error.fr_name }">
            <label>FR Name</label>
            <input ref='fr_name' id="fr-names" v-model="frName" type="text" class="form-control" placeholder="FR name">
          </div>
          <div :class="{ 'form-group col-md-5 ': true, 'has-warning' : error.date_submitted }">
            <label>Date Submitted</label>
            <input v-model="dateSubmitted" type="date" class="form-control" placeholder="Date submitted">
          </div>
          <div :class="{ 'form-group col-md-5 ': true, 'has-warning' : error.amount }">
            <label>Amount</label>
            <input v-model="amount" type="number" class="form-control" placeholder="Enter value">
          </div>
          <div :class="{ 'form-group col-md-5 ': true, 'has-warning' : error.address }">
            <label>Address</label>
            <input id="addresses" v-model="address" type="text" class="form-control" placeholder="Address">
          </div>
          <div :class="{ 'form-group col-md-5 ': true }">
            <button type="submit" class="btn btn-primary btn-lg active">Submit</button>
          </div>
          
       </form>
    </div>
</template>

<script>
    import toastr from 'toastr'
    import alertify from 'alertify.js'
    import 'jquery-ui/themes/base/theme.css';
    import 'jquery-ui/themes/base/core.css';
    import 'jquery-ui/themes/base/theme.css';
    import 'jquery-ui'
    import 'jquery-ui/themes/base/autocomplete.css';
    import 'jquery-ui/ui/widgets/autocomplete';
    import 'jquery-ui/ui/core';

    export default {
        mounted() {
            this.fetchProvinces();
            this.fetchTypes();
            this.fetchAssist();
            this.$refs.fr_name.focus();
        },
        data(){
            return {
                assists: [],
                types: [], provinces: [],
                province: 1,
                typeOfAssistance: 22,
                frName: '',
                dateSubmitted: '',
                amount: '',
                address: '',
                actionTaken: '',
                error: {
                    province: false,
                    type_of_assistance: false,
                    fr_name: false,
                    date_submitted: false,
                    amount: false,
                    address: false,
                    action_taken: false
                }
            }
        },
        methods: {
            autoCompleteFrName(){
                let self = this;
                let names = _.map(self.assists, 'fr_name');
                $( "#fr-names" ).autocomplete({
                  source: _.uniq(names),
                  select: function( event, ui ) {
                    self.frName = ui.item.value;
                  }
                });
            },
            autoCompleteAddress(){
                let self = this;
                let addresses = _.map(self.assists, 'address');
                $( "#addresses" ).autocomplete({
                  source: _.uniq(addresses),
                  select: function( event, ui ) {
                    self.address = ui.item.value;
                  }
                });
            },
            getAssistanceName(){
                let self = this;
                return 'nice';
            },
            submitForm(){
                let self = this;
                self.clearError();
                let form = {
                   province: self.province,
                   fr_name: self.frName,
                   type_of_assistance: self.typeOfAssistance,
                   date_submitted: self.dateSubmitted,
                   amount: self.amount,
                   address: self.address,
                   action_taken: self.actionTaken
                };
                self.$http.post('/assist', form).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.duplicate > 0) {
                            self.error.fr_name = true;
                            self.error.type_of_assistance = true;
                            alertify.confirm('FR Name: <b class="text-primary">' +self.frName.toUpperCase() + '</b> had already granted <i class="text-danger">' + self.getAssistanceName() + ' Assistance</i> ');
                        }else {
                            if (json.assist.id > 0) {
                                self.assists.push(json.assist);
                                self.$refs.fr_name.focus();
                                toastr.success('Successfully granted');
                                self.clearForm();
                            }
                        }
                        
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        let json = resp.body;
                        $.each(json, function(index, val) {
                            self.error[index] = true;
                        });
                    }
                });
            },
            getAssistanceName(){
                let self = this;
                let type = self.typeOfAssistance;
                let rs = _.filter(self.types, {id: type});
                return (rs.length > 0) ? rs[0].name : '-';
            },
            clearError(){
                let self = this;
                $.each(self.error, function(index, val) {
                    self.error[index] = false;
                });
            },
            clearForm(){
                let self = this;
                self.frName = '';
                self.dateSubmitted = '';
                self.amount = '';
                self.address = '';
                self.actionTaken = '';
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
            },
            fetchAssist(){
                let self = this;
                self.$http.get('assist/display/by/type/0').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.assists = json.assists;
                    }
                }, (resp) => {

                })
            }
        },
        watch: {
            'assists': function(newVal){
                let self = this;
                self.autoCompleteFrName();
                self.autoCompleteAddress();
            }
        }
    }
</script>
