
function displayMessages(json) {
	// displays the comments and meta data
	// found in the json data in the display area
	
	display = document.getElementById("displayData");
	display.innerHTML = "";
	
	comments = json.comments;
	for (i=0; i<comments.length; i++) {
		// make div to contain the name, date and comment
		divContainer = document.createElement("div");

		// make div to contain the name and date (metadata)
		divMeta = document.createElement("div");
		paraMeta = document.createElement("p");
		paraMeta.innerText = 'Posted by ' + comments[i]['name'] + ' on ' + comments[i]['day_posted'] + ':';
		divMeta.appendChild(paraMeta);

		// div for the comment
		divComment = document.createElement("div");
		paraComment = document.createElement("p");
		paraComment.innerText = comments[i]['comment'];
		divComment.appendChild(paraComment);

		// append some children
		display.appendChild(divContainer);
		divContainer.appendChild(divMeta);
		divContainer.appendChild(divComment);

		// attributes
		divContainer.setAttribute("class", "comment");
		divMeta.setAttribute("class", "commentMetadata");
		divComment.setAttribute("class", "commentBody");
	}
}





function listenFilter() {
	// when the form for selecting day posted is submitted
	// send an ajax request to the relevant url and
	// call the function to display the messages in the response

	$("#filterForm").submit(function(e) {
		e.preventDefault();
		var filter = $("#selectFilter").val();
		if (filter=="all") {
			url = '/api/comments/past-week/';
		} else if (filter==0) {
			url = '/api/comments/today/';
		} else if (filter==1) {
			url = '/api/comments/1-day-ago/';
		} else {
			url = '/api/comments/'+filter+'-days-ago/';
		}
		$.ajax({
			type: 'GET',
			url: url,
			success: function(data) {
				var json = JSON.parse(data);
				console.log('success');
				displayMessages(json);
			}
		});
	});
}

function listenPost() {
	// when the form for posting a message is submitted
	// do the following...

	$("#postForm").submit(function(e) {
		e.preventDefault();

		if ($('#myMessage').val() == "") {
			// invalid message
			alert('No empty comments!');
		} else {
			// send a POST request to the url with json in the body
			data = '{"comment": "' + $("#myMessage").val() + '", "name": "' + $("#myName").val() + '"}';
			$.ajax({
				type: 'POST',
				url: '/api/comments/',
				data: data,
				success: function(data) {
					console.log("success");
				}
			});
			document.getElementById("myMessage").value = '';
			document.getElementById("myName").value = '';
		}
	});
}

function commentActions() {
	// listen for the forms to be submitted
	listenPost();
	listenFilter();
}





function init() {
	commentActions();
}


window.onload = function() {init();}
