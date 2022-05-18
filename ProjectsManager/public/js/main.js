function getElementValue(id){
	let value = document.getElementById(id).value;
	return value;
}

function getElementInner(id){
	let inner =  document.getElementById(id).innerHTML;
	return inner;
}

function setElementInner(id, value){
	document.getElementById(id).innerHTML = value;
}

function setElementValue(id, value){
	document.getElementById(id).value = value;
}


function changeFormAction(formid, id){
		let action = document.getElementById(formid).action;
		let linkstore = action.includes("store");
		let linkupdate = action.includes("update");
		let positionupd = action.search("update");
		let positionstore = action.search("store");
		let result = action;
		if(!id && linkupdate){
			result = action.slice(0, positionupd) + 'store';
		}
		if(id && linkupdate){
			result = action.slice(0, positionupd) + 'update/' + id;
		}
		if(id && linkstore){
			result = action.slice(0, positionstore) + 'update/' + id;
		}
		//console.log(result); 
		document.getElementById(formid).action = result;
}

function changeFormAction2(formid, id){
		let action = document.getElementById(formid).action;
		let linkstore = action.includes("store");
		let positionstore = action.search("store");
		let result = action;
		if(!id){
			result = action.slice(0, positionstore) + 'store';
		}
		else{
			result = action.slice(0, positionstore) + 'store/' + id;
		}
		//console.log(result); 
		document.getElementById(formid).action = result;
}

function changeId(oldIdValue, newIdValue){
	let elem = document.getElementById(oldIdValue);
	$(elem).attr('id', newIdValue);
}