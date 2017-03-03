<template>
   <div>
    <div class="col-md-12">

        <div class="panel panel-default">
		  <div class="panel-heading">List of Clients 
             <a @click="editTr" :class="{ 'pencil-edit pull-right': true }"><i class="fa fa-2x fa-pencil-square" aria-hidden="true" style="display: inline"></i></a>
             <a class="btn btn-info btn-xs" @click="grantToClient" :class="{ 'pencil-edit pull-right': true }"><i class="fa fa-2x fa-handshake-o" aria-hidden="true"></i> Grant</a>
             <a class="btn btn-info btn-xs">Grant</a>
          </div>
		  <div class="panel-body">
          
		  <input v-model="search" type="text" class="form-control" placeholder="Search" style="width: 220px; border-radius: 25px;">
		  
          <select v-model="selectedType" class="form-control pull-right" style="width: 10%; margin-top: -36px;display: inline">
		  	 <option :value="0">All</option>
		  	 <option :value="type.id" v-for="type in types">
		  	 	{{ type.name }}
		  	 </option>
		  </select><hr>
             <div style="overflow: auto; height: 500px; font-size: 13px">
    		  	 <table class="table table-hover table-striped table-bordered table-condensed">
    		  	 	<thead>
    		  	 		<tr class="text-warning">
    		  	 			<th>PROVINCE</th>
    		  	 			<th>FR NAME</th>
    		  	 			<th class="text-center">TYPE OF ASSISTANCE</th>
    		  	 			<th>ADDRESS</th>
    		  	 			<th>DATE SUBMITTED</th>
    		  	 			<th>ACTION TAKEN</th>
    		  	 			<th style="text-align: right">AMOUNT</th>
                            <th>Date Fund Downloaded to F.O</th>
                            <th width="1">Status</th>
    		  	 		</tr>
    		  	 	</thead>
    		  	 	<tbody>
    		  	 		<tr :id="assist.id" @click="selectFilteredList(assist)" v-for="assist in filteredList">
    		  	 			<td>{{ getProvince(assist.province) }}</td>
    		  	 			<td>{{ assist.fr_name }}</td>
    		  	 			<td class="text-center">{{ getType(assist.type_of_assistance) }}</td>
    		  	 			<td>{{ assist.address }}</td>
    		  	 			<td>{{ formatDate(assist.date_submitted) }}</td>
    		  	 			<td>{{ assist.action_taken }}</td>
    		  	 			<td style="text-align: right"><b>{{ formatAmmount(assist.amount) }}</b></td>
                            <td>{{ dateGranted(assist) }}</td>
                            <td class="text-center text-primary" v-html="checkIfGranted(assist)"></td>
    		  	 		</tr>
    		  	 	</tbody>
                   
    		  	 </table>
             </div>
             <div>
                <h5 class="pull-left" style="font-size: 20px"><b>TOTAL:</b></h5>
                 <h5 class="pull-right text-primary" style="font-size: 20px">P<b>{{ formatAmmount(totalAmount) }}&nbsp;&nbsp;&nbsp;&nbsp;</b></h5>
             </div>
		  </div>
		</div>
    </div>
    <modal-edit-granted
        @assistupdated="childUpdate"
        :provinces="provinces"
        :types="types"
        :modal-granted="modalGranted"
    ></modal-edit-granted>
    <modal-grant @addgranteds="newGranted" :assist="modalGrantPerson" :type="modalGrantType"></modal-grant>
</div>
</template>
<style type="text/css">
    tbody tr {
        cursor: pointer;
    }
    .pencil-edit {
        cursor: pointer;
        margin-left: 10px;
    }
</style>
<script>

	import moment from 'moment'
	import accounting from 'accounting'
	import CompModalEdit from './modal-update-granted.vue'
    import alertify from 'alertify.js'
    import CompModalConfirmGrant from './modal_confirm_grant_to_client.vue'
    export default {
        mounted(){
        	this.fetch();
            this.fetchAllGranted();
        },
        data(){
        	return {
        		assists: [],
        		types: [],
        		provinces: [],
                granteds: [],
        		search: '',
        		selectedType: 0,
                totalAmount: 0,
                currentTrClicked: 0,
                modalGranted: [],
                modalGrantPerson: {},
                modalGrantType: {}
        	}
        },
        components: {
            'modal-edit-granted': CompModalEdit,
            'modal-grant': CompModalConfirmGrant
        },
        methods: {
            dateGranted(assist){
                let self = this;
                let rs = _.filter(self.granteds, {assist_id: assist.id});
                if (rs.length) {
                    let model = _.first(rs);
                    return moment(new Date(model.date)).format('MMMM DD, YYYY, dddd')
                }
            },
            checkIfGranted(assist){
                let self = this;
                let rs = _.filter(self.granteds, {assist_id: assist.id});
                if (rs.length) {
                    return '<i class="fa fa-check" aria-hidden="true"></i>';
                }
            },
            fetchAllGranted(){
                let self = this;
                self.$http.get('/granted/client').then((resp) => {
                    if (resp.status === 200) {
                        let json = resp.body;
                        self.granteds = json;
                    }
                }, (resp) => {
                    console.log(resp);
                });
            },
            newGranted(model){
                let self = this;
                $('#modal-confirm-grant').modal('hide');
                self.granteds.push(model);
                alertify.alert("Successfully granted assistance");
            },
            grantToClient(){
                let self = this;
                let rsAssist = _.filter(self.assists, { id: self.currentTrClicked});
                let assist = (rsAssist.length > 0) ? rsAssist[0] : { fr_name: ''};
                let rsType = _.filter(self.types, {id: assist.type_of_assistance});
                let type = (rsType.length > 0) ? rsType[0] : { name: ''};
                self.modalGrantPerson = assist;
                self.modalGrantType = type;
                $('#modal-confirm-grant').modal('show');
            },
            grantNow(assist){
                /* hits confirm ok, and then grant it now */
                let self = this;
                console.log(assist);
            },
            childUpdate(form){
                let self = this;
                let rs = _.filter(self.assists, {id: form.id});
                if (rs.length) {
                    let model = _.first(rs);
                    model.province = form.province;
                    model.typeOfAssistance = form.type_of_assistance;
                    model.frName = form.fr_name;
                    model.amount = form.amount;
                    model.address = form.address;
                    model.date_submitted = form.date_submitted;
                    model.action_taken = form.action_taken;
                }
            },
            editTr(tr){
                let self = this;
                let rs = _.filter(self.assists, {id: self.currentTrClicked});
                self.modalGranted = rs;
                $('#modal-edit-granted').modal('show');
            },
            selectFilteredList(granted){
                /* when tr is clicked */
                let self = this;
                self.currentTrClicked = granted.id;
                let $el = $(self.$el);
                $el.find('tbody').find('tr').removeClass('text-primary');
                $el.find('tbody tr#'+granted.id).addClass('text-primary');
            },
        	getActionTaken(action){
        		let self = this;
        		if (action === '') {
        			return '-';
        		}
        	},
        	formatAmmount(amount){
        		let self = this;
        		return accounting.formatMoney(amount, '', 2);
        	},
        	formatDate(date){
        		let self = this;
        		let formated = moment(date).format('MMMM DD, YYYY, ddd');
                if (formated === 'Invalid date') {
                    return '-';
                }else {
                    return formated;
                }
        	},
        	getProvince(province_id){
        		let self = this;
        		let rs = _.filter(self.provinces, {id: province_id});
        		return (rs.length > 0) ? rs[0].name : 'Not found';
        	},
        	getType(type){
        		let self = this;
        		let rs = _.filter(self.types, {id: type});
        		return (rs.length > 0) ? rs[0].name : 'Not found';
        	},
        	fetch(){
        		let self = this;
        		self.$http.get('/granted').then((resp) => {
        		    if (resp.status === 200) {
        		        let json = resp.body;
        		        self.types = json.types;
        		        self.provinces = json.provinces;
        		        self.assists = json.assists;
        		    }
        		}, (resp) => {
        		    if (resp.status === 422) {
        		      console.log(resp);
        		    }
        		});
        	},
            caculateTotal(amounts){
                let self = this;
                let amount = 0;
                let total = 0;
                for (var i = 0, len = amounts.length; i < len; i++) {
                    amount = Number(amounts[i]);
                    total += amount;
                }
                self.totalAmount = total;
            }
        },
        computed: {
        	filteredList(){
        		let self = this;
        		let search = self.search.toLowerCase();
        		let rsProvince = [], province = {};
        		let rsTypes = [], type = {};
        		let arr = self.assists.filter(function(index) {
        			rsProvince = _.filter(self.provinces, {id: index.province});
        			rsTypes = _.filter(self.types, {id: index.type_of_assistance});
        			province = (rsProvince.length > 0) ? rsProvince[0].name : '';
        			type = (rsTypes.length > 0) ? rsTypes[0].name : '';
        			return index.fr_name.toLowerCase().indexOf(search) !== -1 ||
        				   index.address.toLowerCase().indexOf(search) !== -1 ||
        				   index.amount.toString().toLowerCase().indexOf(search) !== -1 ||
        				   province.toLowerCase().indexOf(search) !== -1 ||
        				   type.toLowerCase().indexOf(search) !== -1
        		});
                self.caculateTotal(_.map(arr, 'amount'));
                return arr;
        	}
        },

        watch: {
        	'selectedType': function(newVal){
        		let self = this;
        		var resource = self.$resource('assist/display/by/type{/id}');
				  // GET someItem/1
				  resource.get({id: newVal}).then(resp => {
				      if (resp.status === 200) {
				      	 let json =resp.body;
				      	 self.assists = json.assists;
				      }
				  });

        	}
        }
       
    }
</script>
