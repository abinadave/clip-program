<template>
   <!-- Modal -->
    <div class="modal fade" id="modal-confirm-grant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document" style="width: 340px">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          </div>
          <div class="modal-body">
              Do you really want to Grant: <b> {{ type.name }} </b> Assistance <br> <b class="text-success"> {{ assist.fr_name }}
              <hr>
              <label>Date:
                  <input v-model="dateGranted" type="text" class="form-control" id="pickdate">
              </label>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button" class="btn btn-primary" @click="proceedGranting">Yes</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>

	import CompFrom from './form-edit-granted.vue'
  import 'jquery-ui/themes/base/core.css'
  import 'jquery-ui/themes/base/theme.css'
  import 'jquery-ui/themes/base/datepicker.css'
  import 'jquery-ui/ui/core'
  import 'jquery-ui/ui/widgets/datepicker'
  import moment from 'moment'

    export default {
      	props: {
      		 assist: {
              type: Object
           },
           type: {
              type: Object
           }
      	},
        data(){
            return {
                dateGranted: ''
            }
        },
        mounted(){
            let self = this;
            $(function() {
                $('#pickdate').datepicker({
                  dateFormat: "mm/dd/yy",
                  onSelect: function(date) {
                      self.dateGranted = date;
                 },
                });
            });
        },
        methods: {
            proceedGranting(){
                let self = this;
                let isValid = moment(new Date(self.dateGranted)).isValid(); // false
                if (isValid) {
                    self.$http.post('/grant', {
                        date: self.dateGranted,
                        assist_id: self.assist.id
                    }).then(self.succeedGranting, self.errorInGranting)
                }else {
                    let toastr = require('toastr');
                    toastr.warning('Please recheck your date format, enter another date');
                }
                
            },
            succeedGranting(resp){
                let self = this;
                if (resp.status === 200) {
                    let json = resp.body;
                    self.$emit('addgranteds', json);
                }
            },
            errorInGranting(resp){
                /* will fire when error occured */
                console.log(resp);
            }
        },
        components: {

        }
    }
</script>
