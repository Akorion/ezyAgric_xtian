<Style>
.cssload-container {
    margin-top:200px;
	width: 100%;
	height: 100%;
    top:0;
    left:0;
	text-align: center;
    position:fixed !important;
    z-index:300;
    background-color:rgba(0,0,0,0.3);
}

.cssload-speeding-wheel {
	width: 49px;
	height: 49px;
	margin: 40px auto;
	border: 3px solid rgb(255,255,255);
	border-radius: 50%;
	border-left-color: transparent;
	border-right-color: transparent;
	animation: cssload-spin 575ms infinite linear;
   -o-animation: cssload-spin 575ms infinite linear;
   -ms-animation: cssload-spin 575ms infinite linear;
   -webkit-animation: cssload-spin 575ms infinite linear;
   -moz-animation: cssload-spin 575ms infinite linear;

}



@keyframes cssload-spin {
	100%{ transform: rotate(360deg); transform: rotate(360deg); }
}

@-o-keyframes cssload-spin {
	100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
}

@-ms-keyframes cssload-spin {
	100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
}

@-webkit-keyframes cssload-spin {
	100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
}

@-moz-keyframes cssload-spin {
	100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
}
</Style>


<div class="cssload-container">
<div class="cssload-speeding-wheel"></div>
    <p style="color:#fff; font-size:1.8em">Refreshing...</p>
</div>


<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
   <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
</svg>
