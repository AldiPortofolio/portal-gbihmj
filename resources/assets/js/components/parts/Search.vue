<template>
	<transition name="fade">
	    <div class="search modal-mask">
	      <div class="modal-wrapper">
	        <div class="modal-container">
	          	<div class="modal-body">
			        <input type="text" class="searching-input" placeholder="search" required autofocus> <a><i class="fa fa-search searching-icon fa-2x"></i></a>
		        </div>

	          	<div class="modal-footer">
	            	<a @click="closeSearch" class="searching-close"><i class="fa fa-times fa-2x"></i></a>
	          	</div>
	        </div>
	      </div>
	    </div>
	</transition>
</template>

<script>
	export default {
	    methods:{
	    	closeSearch: function () {
	    		this.$emit('close');
			}
		}
   	}
</script>