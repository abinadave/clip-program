<template>
    <div>
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
                }
    		}
    	},
        methods: {
        	submitForm(){
        		let self = this;
        		console.log(self)
        	},
        	
        },
        watch: {
    		'modalGranted': function(newVal){
    			let self = this;
    			console.log(newVal)
    		}
    	}
    }
</script>
