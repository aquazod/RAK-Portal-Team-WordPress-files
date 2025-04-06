<style>
	.zeno_font_resizer a {
		color: white !important;
	}

	.elementor-shortcode {
		color: white;
		font-size: 10px;
	}

	:root {
		--primary-color: #192B45;
		--secondry-color: #8A8B8D;
	}

	#ajax-search-form {
		margin-bottom: 20px;
	}

	#ajax-pagination {
		display: flex;
		justify-content: center; /* Center elements horizontally */
		align-items: center;     /* Center elements vertically (if container has a set height) */
		margin: 20px auto;       /* Ensures the container is treated as a block element */
	}

	.ajax-search-form-btn {
		border: 1px solid #dbdbdb;
		border-radius: 5px;
		color: #333;
		font-family: 'simonson', Arial, sans-serif;
		font-weight: 200;
		margin: 22px 0 0 0 !important;
		padding: 4px 25px;
		text-shadow: 0 1px 0 #fff;
		min-width: auto;
		font-size: 16px;
	}

	.ajax-search-form-btn:hover {
		background-color: #e0e0e0;
		background-position: 0 -15px;
	}

	.ajax-form-row {
		display: flex;
		align-items: center;
		flex-wrap: wrap; /* Allows elements to wrap to the next line if needed */
	}

	.ajax-form-group {
		margin-right: 15px;
		flex: 1;           /* Ensures each group takes equal width in the row */
		min-width: 150px;  /* Prevents elements from shrinking too much */
		border-radius: 5px;
	}

	.ajax-input-group {
		display: flex;
		align-items: center;
	}

	.ajax-input-group img {
		margin-left: 5px;
		cursor: pointer;
	}

	.ajax-form-group:last-child {
		margin-right: 0;
	}

	/* Responsive Styles */
	@media (max-width: 550px) {
		.ajax-form-row {
			flex-direction: column;
			align-items: flex-start;
		}
		.ajax-form-group {
			width: 100%;
			margin-bottom: 15px;
		}
		.ajax-form-group:last-child {
			margin-bottom: 0;
		}
		.ajax-input-group {
			width: 100%;
		}
		.ajax-input-group img {
			margin-left: auto;
		}
		.ajax-page-btn {
			font-size: 0.7em !important;
			width: 70px !important;
		}
		.ajax-page-btn.previousnext {
			width: 100px !important;
		}
	}

	/* General styling for post items */
	.ajax-post-item {
		display: flex;
		align-items: center;
		padding: 25px 10px;
		max-height: 140px;
	}

	.ajax-post-item:nth-child(odd) {
		background: #e5e5e5;
	}

	.ajax-post-thumbnail {
		margin-right: 10px;
		width: 20%;
		padding: 5px;
	}

	.ajax-post-thumbnail img {
		width: 100%;    /* Adjust size as needed */
		height: 90px;
	}

	.ajax-post-title a {
		text-decoration: none !important;
		color: var(--primary-color);
		font-size: 0.8em;
	}

	.ajax-post-title {
		margin-bottom: 5px !important;
	}

	.ajax-post-content {
		flex: 1;
		max-height: 70%;
	}

	.ajax-post-content p {
		margin-bottom: 0 !important;
	}

	.ajax-post-excerpt {
		margin: 5px 0;
	}

	.ajax-post-date {
		color: var(--primary-color);
		font-size: 1em;
	}

	.ajax-read-more-button {
		display: inline-block;
		padding: 5px 10px;
		background-color: var(--primary-color);
		color: #fff;
		border-radius: 5px;
		text-decoration: none !important;
		font-size: 0.9em;
		float: right;
	}

	html[lang="ar"] .ajax-read-more-button {
		float: left;
	}

	html[lang="ar"] .ajax-post-date {
		float: right;
	}

	.ajax-page-btn {
		position: relative;
		float: left;
		line-height: 24px;             /* Using the line-height from provided styles */
		color: #fff;
		text-decoration: none;
		font-weight: 200;
		height: 28px !important;       /* Keeping the height from provided styles */
		filter: none !important;       /* Keeping filter from provided styles */
		background: var(--primary-color) none no-repeat scroll left top;
		border-radius: 0;              /* No border radius as per provided styles */
		padding: 2px 7px;              /* Using padding from provided styles */
		margin: 0 10px 0 0;            /* Margin from provided styles */
		font-size: 1em;                /* Font size from provided styles */
		width: 35px;                   /* Fixed width as per provided styles */
		text-align: center;            /* Text alignment from provided styles */
		white-space: nowrap;           /* Prevent text wrapping */
		overflow: hidden;              /* Hide overflowed text */
		text-overflow: ellipsis;       /* Add ellipsis (...) if text overflows */
		box-sizing: border-box; 
		border: none !important;       /* Include padding and border in width/height */
	}

	.ajax-page-btn.active {
		background: #8A8B8D;
	}

	.ajax-page-btn.previousnext {
		width: 80px;
	}

	@media only screen and (max-width: 550px) {
		.ajax-post-item {
			display: block; /* Stack elements vertically */
			text-align: left; /* Align text to the left */
			height: auto !important;
			max-height: none !important;
		}

		.ajax-post-thumbnail {
			width: 100%; /* Full width for the image */
			margin-bottom: 10px; /* Add spacing below the image */
			height: 100% !important;
		}

		.ajax-post-thumbnail img {
			width: 100%;            /* Ensure the image takes the full width */
			max-height: 218px !important;
			height: 30% !important; /* Maintain aspect ratio */
		}

		.ajax-post-content {
			display: block; /* Stack content vertically */
			max-height: none !important;
		}

		.ajax-post-title,
		.ajax-post-date {
			margin-bottom: 10px; /* Add spacing between elements */
		}

		.ajax-post-excerpt p {
			margin-bottom: 0 !important;
		}

		.ajax-read-more-button {
			display: block;
			margin-bottom: 10px;
			margin-top: 0 !important;
		}
	}

	/* Responsive Styles */
	@media (max-width: 768px) {
		.sidebar-left {
			display: none !important;
		}
		#content-wrap {
			padding: 0 !important;
			margin: 0 !important;
		}
		.site-content.clr {
			width: 100% !important;
			margin-top: 0 !important;
		}
		.content-saver {
			display: block !important;
			flex-direction: column !important;
		}
		.page-heading {
			margin-top: 5px !important;
		}
		.float-left {
			display: none !important;
		}
	}

	/* Sidebar Image Styles */
	.sidebar-image {
		height: 200px;
		margin-top: 20px;
		border-width: 6px 0 0 0;
		border-style: solid;
		border-color: #192B45;
	}

	/* Flex Layout Styles */
	.content-saver {
		display: flex;
		flex-direction: row;
	}

	.sidebar-left {
		width: 24%;
		padding-right: 15px;
		margin: 15px 0 15px 15px;
	}

	#content {
		flex: 1;
		padding-right: 15px;
		background-color: white;
		background: #fff;
		padding: 15px;
		margin-top: 3px;
		box-shadow: inset 0 -0.5em 2em rgb(0 0 0 / 10%), 0 0 0 2px rgb(255 255 255), 0.3em 0.3em 1em rgb(0 0 0 / 30%);
		border-top: 6px solid #192B45;
		margin: 15px;
	}

	/* Additional Styles */
	.homePageLeftLogo {
		background: transparent url('/wp-content/uploads/2024/08/leftlogo.png') no-repeat left top scroll;
		width: 61px;
		height: 205px;
		display: block;
	}

	.float-left {
		position: absolute;
		left: 0;
		margin-top: 100px;
		z-index: 9;
	}

	#homepage-content-wrap {
		margin-bottom: 20px;
		padding-bottom: 20px;
		border-width: 0 0 6px 0;
		border-style: solid;
		border-color: #192B45;
	}

	#homepage-content {
		padding-right: 15px;
	}

	.page-heading {
		width: 100%;
		margin: 15px;
		margin-bottom: 0 !important;
	}

	.page-heading h1 {
		margin-bottom: 0 !important;
	}

</style>