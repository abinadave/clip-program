<template>
    <div>
        <form @submit.prevent="submitForm" class="col-md-8">
	        <div :class="{ 'form-group ': true }">
	            <label>Province</label>
	            <select v-model="province" class="form-control">
	               <option v-for="province in provinces" :value="province.id">
	                 {{ province.name }}
	               </option>
	            </select>
	          </div>
	          <div :class="{ 'form-group': true, 'has-warning' : error.type_of_assistance }">
	            <label>Type of Assistance</label>
	            <select v-model="typeOfAssistance" class="form-control">
	               <option v-for="type in types" :value="type.id">
	                  {{ type.name }}
	               </option>
	            </select>
	          </div>
	          <div :class="{ 'form-group': true, 'has-warning' : error.fr_name }">
	            <label>FR Name</label>
	            <input ref='fr_name' id="fr-names" v-model="frName" type="text" class="form-control" placeholder="FR name">
	          </div>
	          <div :class="{ 'form-group': true, 'has-warning' : error.date_submitted }">
	            <label>Date Submitted</label>
	            <input v-model="dateSubmitted" type="date" class="form-control" placeholder="Date submitted">
	          </div>
	          <div :class="{ 'form-group': true, 'has-warning' : error.amount }">
	            <label>Amount</label>
	            <input v-model="amount" type="number" class="form-control" placeholder="Enter value">
	          </div>
	          <div :class="{ 'form-group': true, 'has-warning' : error.address }">
	            <label>Address</label>
	            <input id="addresses" v-model="address" type="text" class="form-control" placeholder="Address">
	          </div>
              <div :class="{ 'form-group': true, 'has-warning' : error.action_taken }">
                <label>Action Taken</label>
                <textarea style="margin: 0px -32.3438px 0px 0px; height: 116px; width: 382px;" v-model="actionTaken" type="text" class="form-control" placeholder="Action Taken">

                </textarea>
              </div>
             
	          <div :class="{ 'form-group': true }">
	            <button type="submit" class="btn btn-primary btn-lg active">Submit</button>
	          </div>
	       </form>
    </div>
</template>

<script>
    import toastr from 'toastr'
    export default {
    	props: {
    		provinces: {
    			type: Array
    		},
    		types: {
    			type: Array
    		},
    		modalGranted: {
    			type: Array
    		}
    	},
    	data(){
    		return {
                id: 0,
                province: 0,
                typeOfAssistance: 0,
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
                },

    		}
    	},
        methods: {
        	submitForm(){
        		let self = this;
                let form = {
                   id: self.id,
                   province: self.province,
                   fr_name: self.frName,
                   type_of_assistance: self.typeOfAssistance,
                   date_submitted: self.dateSubmitted,
                   amount: self.amount,
                   address: self.address,
                   action_taken: self.actionTaken
                };

        		self.$http.put('/granted', form).then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        if (json.updated) {
                            toastr.success('Successfully updated');
                            self.$parent.$emit('assistupdated', form);
                        }
                    }
                }, (resp) => {
                    if (resp.status === 422) {
                        let json = resp.body;
                        console.log(json)
                    }
                });
        	},
        	
        },
        watch: {
    		'modalGranted': function(newVal){
    			let self = this;
                if (newVal.length) {
                    let granted = _.first(newVal);
                    self.id = granted.id;
                    self.province = granted.province;
                    self.typeOfAssistance = granted.type_of_assistance;
                    self.frName = granted.fr_name;
                    self.amount = granted.amount;
                    self.address = granted.address;
                    self.dateSubmitted = granted.date_submitted;
                    self.actionTaken = granted.action_taken;
                };
    		}
    	}
    }
</script>
