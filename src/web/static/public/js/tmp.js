

function getPagePosition()
{
	var htmlDom = document.documentElement,
		bodyDom = document.body;
	
	return {
		x: htmlDom.scrollLeft || bodyDom.scrollLeft,
		y: htmlDom.scrollTop || bodyDom.scrollTop,
		w: htmlDom.clientWidth || window.innerWidth || bodyDom.clientWidth,
		h: htmlDom.clientHeight || window.innerHeight || bodyDom.clientHeight
	}	
}

function getElePosition(ele)
{
	var sLeft = 0,
		sTop = 0,
		isDiv = /^div$/i.test(ele.tagName),
		position,
		parentPosition;
	
	if (!isDiv) {
		return;	
	}

	sLeft = ele.scrollLeft;	
	sTop = ele.scrollTop;	

	position = {
		x: ele.offsetLeft - sLeft,	
		y: ele.offsetTop - sTop,	
	};
	
	if (ele.offsetParent) {
		parentPosition = getElePosition(ele.offsetParent);
		position.x += parentPosition.x;	
		position.y += parentPosition.y;	
	}

	return position;
}

document.getElementById("dragDiv").offsetTop;
