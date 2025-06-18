<style>
	.centered {
		/* width: ; */
		float: none;
		margin: 20px auto;
	}

	.dropdown-menu {
		padding: 10px;
	}

	.setting-btn {
		background-color: #f2f2f2;
		padding: 5px 10px;
		border-radius: 6px;
		border: 2px #87ceeb solid;
		display: flex;
		align-items: center;
		/* justify-content: space-between; */
	}

	.setting-btn span {
		margin-left: 10px;
		color: gray;
	}

	/* .dropdown-child a:hover {
		color: #232323 !important;
		background: #f3f3f3 !important;
	} */

	.chart-box {
		border-bottom: 5px dashed #f5f5f5;
	}

	.title-chart {
		font-weight: bold;
		padding-bottom: 5px;
	}

	.h3-not-found {
		text-align: center;
		/* height: fit-content; */
		margin: 100px;
		color: #dadada;
		display: flex;
		/* flex-wrap: ; */
		justify-content: center;
	}

	.dropdown-label {
		margin-right: 10px;
	}

	/* table tr{
		margin-bottom: 2px;
	} */
	.progress {
        width: 100%;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        height: 20px;
        background-color: #4caf50;
        text-align: center;
        color: white;
    }
</style>
<?php
	if (aznav('role_table')) {
?>
		<!-- grafik -->
<?php
	} 
?>