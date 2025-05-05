//console.clear();

/*document.getElementById('module-form').dispatchEvent(new CustomEvent('selectTypeField',{detail:{name:this.value,id:this.id}})); */
/*document.getElementById('module-form').addEventListener('selectTypeField', (event)=>0); */

/*document.getElementById('mod_' + this.id).dispatchEvent(new CustomEvent('modMultiForm_Loaded',{detail: this}));*/
/*document.getElementById('mod_' + this.id).addEventListener('modMultiForm_Loaded', (e)=>e.detail); */


document.addEventListener('DOMContentLoaded',function(){
'strict type';
//return;


class selectProduct{
	constructor({count, cost, title, link, label, field_name, type = '', indexProd = 0, article_id = 0}) {
		this.count = count;
		this.cost = cost; 
		this.title = title;
		this.link = link;
		this.label = label;
		this.field_name = field_name;
		this.type = type ? type : 'number';
		this.indexProd = indexProd == 0 ? Date.now() : indexProd;
		this.id = article_id;
	}
	count;
	cost;
	title;
	link;
	label;
	field_name;
	type;
	indexProd;
	id;
}


/*
 * Шаблон списка выбранных продуктов в корзине
 * @param {type} moduleID
 * @param {type} field_name
 * @param {type} costProd
 * @returns {String}
 */
function formatProduct(moduleID, field_name, costProd){
	const pr = costProd;
	
	
	if(fieldsParams[moduleID][field_name] == undefined)
		return '';
	
	
	
	let format = '';
	
	let rounding = fieldsParams[moduleID][field_name]['rounding'] ?? 0;
	
	const formatReplace = (text) => text.
			replaceAll('{title}',		costProd.title)		.replaceAll('{TITLE}',		costProd.title).
			replaceAll('{label}',		costProd.label)		.replaceAll('{LABEL}',		costProd.label).
			replaceAll('{quantity}',	costProd.count)		.replaceAll('{QUANTITY}',	costProd.count).
			replaceAll('{cost}',		costProd.cost)		.replaceAll('{COST}',		costProd.cost).
			replaceAll('{ammount}',		(costProd.count * costProd.cost).toFixed(rounding)).replaceAll('{AMMOUNT}', costProd.count * costProd.cost);//Number.parseFloat(x).toFixed(2);
	
	if(fieldsParams[moduleID][field_name]['label_format_1'] && fieldsParams[moduleID][field_name]['label_format_1'] != undefined)
		format += "<span class='col1'>" + formatReplace(fieldsParams[moduleID][field_name]['label_format_1']) + "</span>";
	if(fieldsParams[moduleID][field_name]['label_format_2'] && fieldsParams[moduleID][field_name]['label_format_2'] != undefined)
		format += "<span class='col2'>" + formatReplace(fieldsParams[moduleID][field_name]['label_format_2']) + "</span>";
	if(fieldsParams[moduleID][field_name]['label_format_3'] && fieldsParams[moduleID][field_name]['label_format_3'] != undefined)
		format += "<span class='col3'>" + formatReplace(fieldsParams[moduleID][field_name]['label_format_3']) + "</span>";
	if(fieldsParams[moduleID][field_name]['label_format_4'] && fieldsParams[moduleID][field_name]['label_format_4'] != undefined)
		format += "<span class='col4'>" + formatReplace(fieldsParams[moduleID][field_name]['label_format_4']) + "</span>";
	
		

	return format;
	
//	if(pr.label)
//		return ` <span class='title'>${pr.title}</span> <span class='fieldLabel'>${pr.label}</span> <span class='countCost'>${pr.count} * ${pr.cost}</span> <span class='total'>${pr.count*pr.cost}</span>`;
	
//	return ` <span class='title'>${pr.title}</span> <span class='countCost'>${pr.count} * ${pr.cost}</span> <span class='total'>${pr.count*pr.cost}</span>`;
}
/*
 * Шаблон итоговой суммы и количества.
 * @param {type} moduleID
 * @param {type} field_name
 * @param {type} quantity
 * @param {type} costTotal
 * @returns {String}
 */
function formatInfo(moduleID, field_name, quantity = 0, costTotal = 0){
	if(costTotal == 0 && quantity == 0)
		return '';
	
	
	if(fieldsParams[moduleID][field_name] == undefined || fieldsParams[moduleID][field_name]['info_format'] == undefined)
		return '';
	
	return fieldsParams[moduleID][field_name]['info_format'].
			replaceAll('{quantity}', quantity).replaceAll('{cost}', costTotal).replaceAll('{QUANTITY}', quantity).replaceAll('{COST}', costTotal);
		
//	return ` <span class='count'>${quantity}</span> <span class='total'>${costTotal}</span>`;
}
/*
 * Обновление инфы на кнопке Корзина.
 * @param string field_name
 * @param int module_id = 0 
 */
function btnInfoUpdate(field_name, module_id = 0){
	
	for(const moduleID of moduleIDs){
		if(! moduleID || ! moduleID in modulesInfo){
			console.log('ModID not Defined');
			continue;
		}
		
		const buttonCart = document.querySelector(`.mfForm.id${moduleID}.mod_${moduleID}`);
		if(buttonCart == null)
			continue;
		
		let labelInfo = buttonCart.querySelector('info.info');
		if(labelInfo == null){
			labelInfo = document.createElement('info');
			labelInfo.classList.add('info');
			labelInfo.field_name = field_name;
			buttonCart.appendChild(labelInfo);
		}
		modulesInfo[moduleID] = labelInfo;
	}
	
	costTotal = 0;
	let fieldParams = null;
	let rounding = 0;
	
	if(module_id && module_id in fieldsParams  &&  field_name in  fieldsParams[module_id])
		fieldParams = fieldsParams[module_id][field_name] ?? null;
	if(fieldParams)
		rounding = fieldParams.rounding
	
	selectsProducts = selectsProducts.filter(clear => clear);
	
	for(const modProduct of selectsProducts){
		costTotal += modProduct.count * modProduct.cost;
	}
	
	
	for(const [modID, modInfo] of Object.entries(modulesInfo)){
		if(modInfo != undefined && modInfo.field_name == field_name)
			modInfo.innerHTML = formatInfo(modID, modInfo.field_name, selectsProducts.length, (costTotal).toFixed(rounding));//Number.parseFloat(x).toFixed(2);
		
		if(modInfo.innerText == ''){
			modInfo.classList.add('empty');
			modInfo.classList.remove('full');
		}else{
			modInfo.classList.remove('empty');
			modInfo.classList.add('full');
		}
	}
	
	for(const [modID, modInfo] of Object.entries(modulesInfoInForm)){
		if(modInfo != undefined && modInfo.field_name == field_name){
			
//			document.getElementById('cost' + modInfo.field_name + modInfo.modID);
//			document.getElementById('count' + modInfo.field_name + modInfo.modID);
			
			
//			modInfo.innerHTML = formatInfo(modID, modInfo.field_name, selectsProducts.length, costTotal);
			
			
	
	
			
			let fld_cost = document.getElementById(modInfo.dataset.idcost);
			let fld_count = document.getElementById(modInfo.dataset.idcount);
			
			if(fld_cost)
				fld_cost.value = costTotal;
				
			if(fld_count){
				fld_count.value = selectsProducts.length ? selectsProducts.length : ''; 
//				if(costTotal == 0 && (fieldsParams[modID][field_name].require ?? false))
//					fld.value = '';
//				else
//					fld.value = costTotal;
			}
		}
		
		if(modInfo.innerText == ''){
			modInfo.classList.add('empty');
			modInfo.classList.remove('full');
		}else{
			modInfo.classList.remove('empty');
			modInfo.classList.add('full');
		}
	}
//	return 
}




function fieldsUpdate(selectProduct, input = null){
	const selProd = selectProduct;
//field_title, indexProd, , , 
//console.log(input.parentNode, input.indexProd, input.field_title, input.field_href, input.field_name, input.field_label);
	
//	let ar = [];
//	
//	for(let f of fields){
//		console.log(f.parentNode, f.indexProd, f.field_title, f.field_href, f.field_name, f.field_label);
//		
//		let a = {};
//		a.title		= f.field_title == selProd.title;
//		a.href		= f.field_href	== selProd.link;
//		a.fieldname	= f.field_name	== selProd.field_name;
//		a.label		= f.field_label == selProd.label;
//		
//		if(Array.from(a).every(all => all))
//			ar.push(f);
//	}
	
//	let fldsFiltered = fields.filter(f => f && f.indexProd == selProd.indexProd || 
//			f && f.field_title == selProd.title && f.field_href == selProd.link && f.field_name == selProd.field_name && f.field_label == selProd.label);
	
	for(let fld of fields.filter(f => f && f.indexProd == selProd.indexProd || 
			f && f.field_title == selProd.title && f.field_href == selProd.link && f.field_name == selProd.field_name && f.field_label == selProd.label)){
		
		const lbl = fld.label ?? null;// fld.closest('label');
		const span = fld.span ?? null;// lbl ? lbl.querySelector('span') : null;
		
		if(span && selProd.count > 0)
			span.innerText = fld.field_label_selected ? fld.field_label_selected : fld.field_label_select;
		if(span && selProd.count == 0)//fld.type != 'hidden' && 
			span.innerText = fld.field_label_select;
	
		if(fld == input)
			continue;
		
		
		
		
		if(fld.type == 'checkbox')
			fld.checked = selProd.count > 0 ? true : false;//count ? true: false; // input.checked;
		else
			fld.value = selProd.count;//count;  // input.value;
	}
}
/**
 * Обновление выбранного списка товаров в корзине.
 * @param selectProduct selectProduct
 * @param HTMLElement input
 * @returns void
 */
function labelsUpdate(selectProduct, input = null){
	const selProd = selectProduct;
	
	for(const [i, lbl] of modulesLabelsAll.entries()){
		
//		const fieldParams = fieldsParams[inptChange_storageSave.module_id][field_name];
//		const fieldParams = fieldsParams[lbl.module_id][lbl.field_name];
		// Проверка на количество или на только один товар в корзине
		if(selProd.count > 0 || lbl.indexProd != selProd.indexProd || 
				lbl.field_title != selProd.title || lbl.field_href != selProd.link || lbl.field_name != selProd.field_name || lbl.label != selProd.label)//  || ! (fieldParams.onlyone) && lbl.input == inptChange_storageSave
			continue;
		
		
//		const i = selectsProducts.indexOf(selProd);
		delete selectsProducts[i];
		if(input)
			localStorage.removeItem('multiForm:calculator:' + selProd.indexProd);
		
		removeProductLabel(selProd, input);
		btnInfoUpdate(selProd.field_name, lbl.module_id);
	continue;
		
		
		
		lbl.parentNode.removeChild(lbl);
		lbl.remove();
		
		const fld		= lbl.querySelector('input');
		const _i_fld	= fields.indexOf(fld);// .findIndex(f => f == fld);
		delete fields[_i_fld];
			
		const _i_lbl = modulesLabelsAll.indexOf(lbl);
		
		delete modulesLabelsAll[_i_lbl];
	}
	
}

/*
 * Удаление Label продукта во всех модулях, и удаление выбранного продукта
 * Происходит при нажатии кнопки удалить.
 * @param {type} e
 * @param {type} selectProduct
 * @returns {Boolean}
 */
function removeProductLabel(selectProduct){//, labelProductCart, fieldProductCart
	
	if(selectProduct == null)
		return null;
	
	/* Удаляем все Label с выбранным продуктом во всех модулях при нажатии кнопки удаления */
	/* Удаляем все Field продукта в корзине с выбранным продуктом во всех модулях при нажатии кнопки удаления */
	for(const lbl of modulesLabelsAll.filter(lbl => lbl && lbl.indexProd == selectProduct.indexProd)){
		const fld = lbl.querySelector('input');
		if(fld){
			fld.parentNode.removeChild(fld);
			fld.remove();
			const fldIndex = fields.indexOf(fld);
			delete fields[fldIndex];
		}
		
		lbl.parentNode.removeChild(lbl);
		lbl.remove();
		
		const lblIndex = modulesLabelsAll.indexOf(lbl);
		delete modulesLabelsAll[lblIndex];
	}
	
	for(const fld of fields.filter(f => f.indexProd == selectProduct.indexProd)){
		if(fld.type == 'checkbox'){
			fld.checked = false;
		}else{
			fld.value = 0;
		}
		
		if(fld.type == 'hidden')
			continue;
		
		const lbl = fld.label ?? null;//.closest('label');
		const span = lbl.span ?? null;//.querySelector('span');
		
		if(span && selectProduct.count > 0)
			span.innerText = fld.field_label_selected ? fld.field_label_selected : fld.field_label_select;
		if(span && selectProduct.count == 0)
			span.innerText = fld.field_label_select;
	}
	
	
	fields = fields.filter(clear => clear);
	modulesLabelsAll = modulesLabelsAll.filter(clear => clear);
	selectsProducts = selectsProducts.filter(clear => clear);
	
	
	
	
	return false;
}
/**
 * Добавление продукта в список < UL > в корзине
 * @param INT modID
 * @param selectProduct selectProduct
 * @returns {HTMLElement|HTMLLabelElement|null}
 */
function addProductLabel(modID, selectProduct){
	
	const list = document.querySelector(`#list${modID}.calculatorList`);
	
	if(list == null)
		return null;
	
	let lbl = null;
	let fld = null;
	let fld_opt = null;
	
	const lbls = Array.from(list.querySelectorAll('label')).filter(f => f.indexProd == selectProduct.indexProd);
	if(lbls.length > 0){
		lbl		= lbls.shift();
		fld		= lbl.querySelector('input');
		fld_opt = lbl.querySelector('input.option[type="hidden"]');
	}else{
		lbl		= document.createElement('label');
		fld		= document.createElement('input');
		fld_opt = document.createElement('input');
		fld_opt.classList.add('option');
		fld_opt.type = 'hidden';
		
		modulesLabelsAll.push(lbl);
	}
	fld_opt.value = JSON.stringify(selectProduct);
	
	lbl.span = null;
	lbl.input = fld;
	lbl.option = fld_opt;
	lbl.field_name = selectProduct.field_name;
	lbl.field_title= selectProduct.title;
	lbl.innerHTML = formatProduct(modID, selectProduct.field_name ?? '', selectProduct);
	lbl.indexProd = selectProduct.indexProd;
	lbl.module_id = modID;
	lbl.article_id = selectProduct.id; // <--ArticleID
//	lbl.prototype.toString = ()=>`${selectProduct.indexProd} /${selectProduct.title} - ${selectProduct.cost} =  - ${selectProduct.count}`;
	
	fld.type  = selectProduct.type == 'checkbox' ? 'hidden' : (selectProduct.type ? selectProduct.type : 'number');
	
	fld.value = selectProduct.count;
	
	
	if(fld.type == 'checkbox')
		fld.checked = selectProduct.count > 0 ? true : false;
	
	if(lbls.length > 0)
		return lbl;
	
	fld.classList.add('form-control');
	fld.article_id = selectProduct.id;	// <-- ArticleID fieldsParams[modID][selectProduct.field_name].article_id ?? 0;
	fld.module_id = modID;
	fld.span = null;
	fld.label = lbl;
	fld.field_name = selectProduct.field_name;
	fld.name = selectProduct.field_name + `[counts][]`;
	fld.indexProd  = selectProduct.indexProd;
	fld.min  = 0;
	lbl.appendChild(fld);
	lbl.appendChild(fld_opt);
	
	fld_opt.indexProd  = selectProduct.indexProd;
	fld_opt.field_name = selectProduct.field_name;
	fld_opt.module_id = modID;
	fld_opt.label = lbl;
	fld_opt.name  = selectProduct.field_name + '[options][]';
	
	let field_format = 'int';
	let fieldParams = null;
	
	if(fieldsParams[modID])
		fieldParams = Object.entries(fieldsParams[modID]).filter((param, fld_name) => fld_name == selectProduct.field_name);
	
	if(fieldParams.length){
		field_format = fieldParams.pop().field_format ?? 'int';
	}
	
	fld.addEventListener('change', () => {
		let count = field_format == 'int' ? Number.parseInt(fld.value) : parseFloat(fld.value);
		if(fld.type == 'checkbox')
			count = fld.checked && count ? count : 0;
		calculate(count, selectProduct.cost, selectProduct.title, selectProduct.link, selectProduct.label, selectProduct.field_name, selectProduct.type, selectProduct.id, fld, selectProduct.indexProd)
	});
	
	
	const btnDelete = document.createElement('button');
	btnDelete.classList.add('btn');
	btnDelete.classList.add('button');
	btnDelete.innerHTML = '❌';
	btnDelete.addEventListener('click', (e) => e.preventDefault() || 
//			removeProductLabel(selectProduct) || btnInfoUpdate(selectProduct.field_name);
			calculate(0, selectProduct.cost, selectProduct.title, selectProduct.link, selectProduct.label, selectProduct.field_name, selectProduct.type, selectProduct.id, fld, selectProduct.indexProd)
		);
	lbl.appendChild(btnDelete);
	
	fields.push(fld);
	list.appendChild(lbl);
	
	return lbl;
}


/**
 * 
 * @param object moduleParam
 * @param int modID
 * @param selectProduct selectProduct
 * @returns int quantity select product
 */
function eventModuleLoaded(moduleParam, modID, selectProduct = null){
	const info = document.querySelector(`#info${modID}.calculatorInfo`);
	if(info == null)
		return null;
	info.modID = modID;
	info.field_name = selectProduct.field_name;
	modulesInfoInForm[modID] = info;
	
	btnInfoUpdate(selectProduct.field_name, modID);
	
	return selectProduct.count;
}

//console.clear();
//console.log(document.querySelectorAll('script.modMultiForm.json.calculator'));

/* Список полей для указнания количества */
let fields = [];
/* Список параметров полей с ключём имени поля 
 * fieldsParams[moduleID][field_name] = {} */
let fieldsParams = {};
/*  */
let costTotal	= 0;
/* Список выбранных продуктов */
let selectsProducts = []; // 
/*  */
let moduleIDs = [];
/* Массив HTMLэлементов модулей с информациями о количестве и сумме */
let modulesInfo = {}; 
/* Массив HTMLэлементов модулей с информациями о количестве и сумме */
let modulesInfoInForm = {}; 
/* Массив HTML Label всех модулей с продуктами о количестве и сумме, массив нужен для удаления HTML элементов */
let modulesLabelsAll = [];


/**
 * 
 * @param int count
 * @param int cost
 * @param string title
 * @param string link
 * @param string label
 * @param string field_name
 * @param string type
 * @param string article_id
 * @param HTMLElement|null inputChange_storageSave
 * @param int indexProd
 * @returns selectProduct
 */
function calculate(count = 0, cost = 0, title = '', link = '', label = '', field_name = '', type = '', article_id = 0, inputChange_storageSave = null, indexProd = 0){

//console.log(`function Calculate: ( count:${count}, cost:${cost}, title:${title}, label:${label}, link:${link} ) !!!`);
//	mfPanelForm  1 field300 static-body id330  mf
//	mfForm mfGo link id175 mod_175 pos_footer 0 default btn btn-primary button   v
//	mfForm id123 mod_123
	const input = inputChange_storageSave;
	
	if(input && input.type == 'checkbox' && input.checked == false)
		count = 0;
	
	if(input && type == '')
		type = input.type;
	
	
	
	if(article_id == 0 && input && fieldsParams[input.module_id] != undefined && field_name in fieldsParams[input.module_id])
		article_id = fieldsParams[input.module_id][field_name]['article_id'] ?? 0;
	else{
		// доработать код из комментария для получения ID материала от туда
		//article_id = JSON.parse(document.querySelector('script[type="application/ld+json"]').innerText)['@graph']
	}
	
	let selProd = null;
	
	if(indexProd == 0)
		indexProd = Date.now();
		
	/* Удаляем из массива продуктов изменяемый */
	const propIndex = selectsProducts.findIndex(prod => prod && prod.indexProd == indexProd
			|| prod && prod.title == title && prod.link == link && prod.label == label && prod.field_name == field_name);
		
	if(propIndex == -1 && count == 0)
		return null;
	
	if(propIndex == -1){
		selProd = new selectProduct({count: count, cost: cost,  title:title, link:link, label:label, field_name: field_name, type: type, indexProd: indexProd, article_id:article_id}) ;
	
		
		
//JSON.parse(document.querySelector('script[type="application/ld+json"]').innerText)['@graph']

		
		selectsProducts.push(selProd);
	} else {// Создание нового объекта продукта
		selProd = selectsProducts[propIndex] ?? null;
		indexProd = selProd.indexProd;
	}
	
	if(selProd == null || selProd == undefined)
		return null;
	
	selProd.count		= count;
	selProd.cost		= cost;
	
	if(selProd.count == 0){
		delete selectsProducts[propIndex];
	}
	
	if(selProd.count == 0 && input){
		removeProductLabel(selProd);
		localStorage.removeItem('multiForm:calculator:' + selProd.indexProd);
	}
	if(selProd.count > 0 && input){
		localStorage.setItem('multiForm:calculator:' + selProd.indexProd, JSON.stringify(selProd));
	}
	
	
	
	
	btnInfoUpdate(selProd.field_name);
	fieldsUpdate(selProd, input);
	labelsUpdate(selProd, input);
	
//	const onlyone = input ? (fieldsParams[input.module_id][field_name].onlyone ?? false) : false;
	
	/* (ТОЛЬКО ОДИН) удаление остальных выбранных продуктов */
	if(input && input instanceof Object  && input.onlyone == 'article'){
		for(const [i_selProd, sProd] of selectsProducts.entries()){
			if(selProd == sProd || sProd.indexProd == selProd.indexProd || (sProd == undefined))
				continue;
			
			const i = selectsProducts.indexOf(sProd);		
			removeProductLabel(sProd, input);
			calculate(0, sProd.cost, sProd.title, sProd.link, sProd.label, sProd.field_name, sProd.type, sProd.id, true, sProd.indexProd);
		}
	}
	/* (ТОЛЬКО ОДИН для Материала/Статьи) удаление остальных опций каждого выбранного продукта */
	if(input && input instanceof Object  && input.onlyone == 'label'){
		for(const [i_selProd, sProd] of selectsProducts.entries()){
			if(sProd == selProd || 
					sProd.title == selProd.title && sProd.link == selProd.link && sProd.label == selProd.label
					|| sProd.indexProd == selProd.indexProd || (sProd == undefined))
				continue;
			
			const i = selectsProducts.indexOf(sProd);		
			removeProductLabel(sProd, input);
			calculate(0, sProd.cost, sProd.title, sProd.link, sProd.label, sProd.field_name, sProd.type, sProd.id,  true, sProd.indexProd);
		}
	}
	
	
	
	
//	if(inputChange_storageSave == null || 
//			0 == modulesLabelsAll.filter(lbl => lbl.module_id == modID 
//			&& lbl.indexProd == selectProduct.indexProd).length)
//		return addProductLabel(modID, selectProduct);
	
	fields				= fields.filter(clear => clear);
	modulesLabelsAll	= modulesLabelsAll.filter(clear => clear);
	selectsProducts		= selectsProducts.filter(clear => clear);
	
	for(const modID of moduleIDs){
//		const module = document.getElementById('mod_' + modID);
		const info = document.querySelector(`#info${modID}.calculatorInfo`);
		
		const check_addProductLabel = inputChange_storageSave == null || 0 == modulesLabelsAll.filter(lbl => lbl.module_id == modID && lbl.indexProd == selectProduct.indexProd).length;
	
//		if(info == null){
//			document.getElementById('mod_' + modID).addEventListener('modMultiForm_Loaded', 
//				module => eventModuleLoaded(module, modID, selProd, input)
//					&& check_addProductLabel && addProductLabel(modID, selectProduct));
//		}
		if(info){
			eventModuleLoaded(null, modID, selProd, input) && check_addProductLabel && addProductLabel(modID, selProd);
		}
//		document.getElementById('mod_' + modID).addEventListener('click', 
//			module => eventModuleLoaded(module, modID, selProd, input)
////					&& check_addProductLabel && addProductLabel(modID, selectProduct)
//		);
	}
	
	return selProd;
}




function Init(){
	
	
//console.log('ExecuteCalculatorJS');
	
/* Загрузка выбранных продуктов из кеша хранилища браузера,
 *  Удаление дубликатов продуктов  */
for(let key of Object.keys(localStorage).filter((item)=>item.startsWith('multiForm:calculator:'))) //.concat(Object.keys(sessionStorage)).filter((item, pos, locStor)=>locStor.indexOf(item) == pos)
{
	const prodVal = localStorage.getItem(key);
	if( ! prodVal){
		localStorage.removeItem(key);
		continue;
	}
	
	const sProd = new selectProduct(JSON.parse(prodVal));
	
//	const prods = selectsProducts.filter(prod => prod.title == sProd.title && prod.link == sProd.link && prod.label == sProd.label &&	 prod.field_name == sProd.field_name);
//	let indexDuplicate = -1
//	for(const prod of prods){
//		localStorage.removeItem('multiForm:calculator:' + prod.indexProd);
//		indexDuplicate = selectsProducts.indexOf(prod);
//		selectsProducts[indexDuplicate] = null;
//	}
	
	let indexDuplicate = selectsProducts.findIndex(prod => prod && prod.indexProd ==  sProd.indexProd 
		|| prod && prod.title == sProd.title && prod.link == sProd.link && prod.label == sProd.label &&	 prod.field_name == sProd.field_name);

	if(indexDuplicate == -1){
		selectsProducts.push(sProd);
	}else{
		localStorage.removeItem('multiForm:calculator:' + selectsProducts[indexDuplicate].indexProd);
		selectsProducts[indexDuplicate] = sProd;
	}
}

selectsProducts = selectsProducts.filter(clear => clear);

//console.log('ExecuteCalculatorJS', Array.from(document.querySelectorAll('script.modMultiForm.json.calculator')));

for(const json of Array.from(document.querySelectorAll('script.modMultiForm.json.calculator'))){
//console.log(document.querySelectorAll('script.modMultiForm.json.calculator'));
//console.log('fieldParams',json.textContent);
	const fieldParams = JSON.parse(json.textContent);
console.log(document.querySelectorAll('script.modMultiForm.json.calculator'),'fieldParams',fieldParams);
	
	if(fieldsParams[fieldParams.module_id] == undefined)
		fieldsParams[fieldParams.module_id] = {};
	fieldsParams[fieldParams.module_id][fieldParams.field_name] = fieldParams;
	
	
	fieldParams.field_id; // class, field, intNumberID
	
	
//	fieldParams = {	field_name, field_id, field_type, field_format, module_id, }
//	fieldsParams[fieldParams.module_id][fieldParams.field_name] = fieldParams;
//console.log('fieldsParams',fieldsParams);

	moduleIDs.push(fieldParams.module_id);

	
	const list = document.querySelector(`#list${fieldParams.module_id}.calculatorList`);
	if(list){
		for(const selProd of selectsProducts)
			addProductLabel (fieldParams.module_id, selProd);
	}else{
		
//		const check_addProductLabel = inputChange_storageSave == null || 0 == modulesLabelsAll.filter(lbl => lbl.module_id == modID && lbl.indexProd == selectProduct.indexProd).length;
//				module => eventModuleLoaded(module, modID, selProd, input)
//					&& check_addProductLabel && addProductLabel(modID, selectProduct);
		
//			console.log(fieldParams.module_id, selProd) || 
		const mod = document.getElementById('mod_' + fieldParams.module_id);
		if(mod){
			mod.addEventListener('modMultiForm_Loaded', module => 
				selectsProducts.forEach((selProd) => addProductLabel(fieldParams.module_id, selProd) && eventModuleLoaded(null, fieldParams.module_id, selProd))
			);
			mod.addEventListener('click', 
				e => selectsProducts.forEach((selProd) => eventModuleLoaded(null, fieldParams.module_id, selProd))
			);
		}
		
	}
//		module.id;
//		module.button;	//	id button element
//		module.buttons;	//	array html elements buttons
//		module.captcha;
//		module.control;	//	html element button
//		module.fields;	//	array field name
//		module.tag;		//	tag name button
//		module.type;	//	type form: popup, static
//		module.toggle;	//	modal
	
	
//document.getElementById('mod_' + this.id).dispatchEvent(new CustomEvent('modMultiForm_Loaded',{detail: this}));
/*document.getElementById('module-form').dispatchEvent(new CustomEvent('selectTypeField',{detail:{name:this.value,id:this.id}})); */
/*document.getElementById('module-form').addEventListener('selectTypeField', (event)=>0); */

// ------------------------------------------------------------	
	
	
	fieldParams.field_id; // class, field, intNumberID
	fieldParams.class_article;
	fieldParams.class_title;
	fieldParams.class_link; 
	fieldParams.class_option; 
	fieldParams.class_field; 
	fieldParams.class_label; 

console.log('field_id',fieldParams.field_id);
console.log('class_article',fieldParams.class_article);
console.log('class_title',	fieldParams.class_title);
console.log('class_link',	fieldParams.class_link);
console.log('class_option',	fieldParams.class_option);
console.log('class_field',	fieldParams.class_field);
console.log('class_label',	fieldParams.class_label);
//console.log('fieldParams',fieldParams);
	
	/* Показывает только способ определеения полей*/
	const field_select = ['class','field'].includes(fieldParams.field_id) ? fieldParams.field_id : '';
	
	/* field_id - только ID поля */
	if(field_select)
		fieldParams.field_id = 0;
	
	let art_selector = '';
	
	if(field_select == 'class')
		art_selector = fieldParams.class_article;
	
	if(field_select == 'field')
		art_selector = '.com-content-article, .item-page, .item-content, article';//, .item-content, .item-page, .com-content-article
	
//	if(fieldParams.field_id && fieldParams.field_name_value)
//		art_selector = '.fields-container > .field-entry.' + fieldParams.field_name_value + ' > .field-value';
	
	if(art_selector == ''){
		art_selector = '.com-content-article, .item-page, .item-content, article';
	}
	
//	if(art_selector == ''){
//		console.log('!!! Empty Class(article) for USE select fields Module <<<---');
//		return;
//	}
		
	let class_option = ''; // ' .field-entry'
	
	
	
	
	const arts_cost = document.querySelectorAll(art_selector);
	
	

	let flds_selector = '';
	
	if(field_select == 'class')
		flds_selector = fieldParams.class_field;
	
	if(field_select == 'field')
		flds_selector = ' :is(input[type=number],input[type=checkbox],input[type=radio])';//' input[data-cost][data-label]';
	
	if(flds_selector == '' && fieldParams.field_name_value)
		flds_selector = ' .field-entry.' + fieldParams.field_name_value + ' > .field-value';
	
	
	if(fieldParams.field_id && fieldParams.field_name_value)
		flds_selector = ' .field-entry.' + fieldParams.field_name_value + ' > .field-value';
	
	
	if(flds_selector == ''){
		flds_selector = ' .field-entry > .field-value, .field-entry.field-value, .field'; 
//		class_option = ' .field-entry';
	}
	
	
	
//console.log('flds_selector',flds_selector);
	
	let fldElements = [];
	
	for(const art_cost of Array.from(arts_cost)){
		
		const flds_cost = art_cost.querySelectorAll(flds_selector);
		
	for(const fld_cost of Array.from(flds_cost)){
		
		if(fldElements.includes(fld_cost))
			continue;
		
		
		let inputParent = fld_cost;
		
		if(fld_cost.tagName == 'INPUT')
			inputParent = fld_cost.parentNode;
			
		
		/*  */
		let article_title = null;
		
		/*  */
		let title = '';
		
		if(fieldParams.field_id == 'field')
			title = fld_cost.dataset?.title;
		
		/*  */
		let class_title = '';
		
		if(! title && fieldParams?.class_title)
			article_title = art_cost.querySelector(fieldParams.class_title);
		if(! article_title)
			article_title = art_cost.querySelector('.item-title, .page-header a, a.page-header, .page-header a, .page-header > :is(h1,h2,h3,h4,h5,h6), .page-header:is(h1,h2,h3,h4,h5,h6), a.page-header, a');
		if(article_title)
			title = article_title.innerText.trim();
		else{
			title = document.title;
			article_title = art_cost;
		}
		
		/*  */
		let	prodCost	= fld_cost.dataset?.cost;
		/*  */
		let label		= fld_cost.dataset?.label;
		/*  */
		let option_label = null;
		/*  */
		let class_label = fieldParams.class_label;
		
		if(! label && fieldParams.class_label && fieldParams.class_label != fieldParams.class_field ){
			if(fieldParams.class_option)
				label = fld_cost.closest(fieldParams.class_option)?.querySelector(fieldParams.class_label)?.innerText?.trim();
			if(fieldParams.class_option == fieldParams.class_field)
				label = fld_cost.querySelector(fieldParams.class_label)?.innerText?.trim();
		}
		if(! label && fieldParams.class_option && fieldParams.class_option == fieldParams.class_label
			&& fieldParams.class_label != fieldParams.class_field )
		{
			label = fld_cost.closest(fieldParams.class_label)?.innerText?.trim();
		}
		
		if(! label && ((! fieldParams.class_option || fieldParams.class_option == fieldParams.class_field)
			|| ! fieldParams.class_label|| fieldParams.class_label == fieldParams.class_field)){
			//option_label
			label = fld_cost?.innerText?.trim();
			
			let index = label.indexOf(':');
			
			if(index > -1){
				prodCost	= parseFloat(label.substr(0,index)??0);
				label		= label?.substr(index+1)?.trim();
				fld_cost.innerText = '';
			}
			else if((index = label.lastIndexOf('-')) > -1 ){
				prodCost	= parseFloat(label.substr(index+1)??0);
				label		= label?.substr(0,index)?.trim();
				fld_cost.innerText = '';
			}
			
		}
			
		
		if(! label && class_option)
			label = fld_cost.closest(class_option).querySelector('a, label')?.innerText?.trim();
		
//		if(inputParent.tagName == 'INPUT' && inputParent.dataset?.label)
//			label	= inputParent.dataset?.label;
//		
//		if(_inpt.tagName == 'INPUT' && _inpt.dataset?.label)
//			label	= _inpt.dataset?.label;
		
//console.log('label',label);
//console.log('prodCost',prodCost);
		
//console.log('fld_cost',fld_cost);
		const a_tag = article_title && article_title.tagName == 'A' ? article_title : article_title.querySelector('a');
		
		let a_href = a_tag ? a_tag.href.replace(window.location.origin, '') : location.pathname;
//console.log(article_title, title, '   a_href',a_href);
		
//		a_href = ((article_title.tagName == 'a' ? article_title : article_title.querySelector('a')) ?? location).href;
//		a_minus = document.querySelector('base') ? document.querySelector('base').href : 
		
		
//		document.querySelector('base') ?? ''
		/*  */
		let _inpt = null;

		if(['INPUT'].includes(fld_cost.tagName))
			_inpt = fld_cost;
		else
			_inpt = document.createElement('input');
		
		
		fldElements.push(_inpt);
		fields.push(_inpt);
	
		let class_field = '';
	
		/* находит одиночные класы поля  в фильтре и вставляет их в поле в случае когда нет классов у поля */
		if(_inpt.classList.length == 0 && (typeof fieldParams.field_id == "number" || fieldParams.field_id == 'field')){
			class_field = fieldParams.class_field.split(',').filter(
				(cls) => !cls.trim().includes(' ') && !cls.includes('>') && !cls.includes('[') && !cls.includes('+') && !cls.trim()
			);
			if(class_field.length && class_field[0] != '')
				_inpt.classList.add(class_field.at(0));
			else 
				_inpt.classList.add('form-control');
		}
//		document.querySelectorAll('script.modMultiForm.json.calculator')
	
		_inpt.style.order = 1;
		_inpt.field_title = title;
		_inpt.field_label = label;
		_inpt.field_label_select = fieldParams.field_label_select;
		_inpt.field_label_selected = fieldParams.field_label_selected;
		_inpt.field_href = a_href;
		_inpt.field_type = fieldParams.field_type;
		_inpt.field_name = fieldParams.field_name;
		_inpt.article_id = fieldParams.article_id;
		_inpt.module_id	 = fieldParams.module_id;
		_inpt.onlyone	 = fieldParams.onlyone;
//		_inpt.setAttribute("type", "number");
//		_inpt.classList.add('form-control');
		_inpt.classList.add(fieldParams.field_type);
		_inpt.classList.add(fieldParams.field_name);
		if(fieldParams.field_name_value)
			_inpt.classList.add(fieldParams.field_name_value);
		
		if(fieldParams.field_type == 'button')
			_inpt.type = 'checkbox';
		else
			_inpt.type = fieldParams.field_type;
		
		
		if(fieldParams.field_type == 'button'){
			_inpt.classList.add('btn-check');
			_inpt.classList.add('hidden');
			_inpt.autocomplete = 'off';
			_inpt.value = 1;
//			_inpt.textContent = fieldParams.field_title;
//			_inpt.innerText = fieldParams.field_title;
//			_inpt.innerHTML = fieldParams.field_title;
		}else{
			_inpt.classList.add('input');
			_inpt.value = 0;
			
		}
		
		if(! _inpt.min)
			_inpt.min = 0;
		
		
		if(_inpt.max && _inpt.value < _inpt.max)
			_inpt.value = _inpt.max;
		
		
		if(inputParent.tagName == 'INPUT' && inputParent.dataset?.title)
			title	= inputParent.dataset?.title;
		
		if(_inpt.tagName == 'INPUT' && _inpt.dataset?.title)
			title	= _inpt.dataset?.title;
		
		_inpt.field_title = title;
		_inpt.indexProd = 0;
		

		
//		let		prodCount	= fieldParams.field_format == 'int' ? Number.parseInt(_inpt.value) : parseFloat(_inpt.value);
//				prodCount	= fieldParams.field_type == 'button' && _inpt.checked == false ? 0 : prodCount;
		
		if(inputParent.tagName == 'INPUT' && inputParent.dataset.cost)
			prodCost	= inputParent.dataset.cost;
		
		if(_inpt.tagName == 'INPUT' && _inpt.dataset.cost)
			prodCost	= _inpt.dataset.cost;
		
		if(! prodCost && inputParent.tagName != 'INPUT' && ! inputParent.dataset.cost){
			prodCost	= parseFloat(inputParent.innerText.trim());
			inputParent.innerText = '';
		}
			
		
		
		
//		const click = () => prodCount >= 0 && prodCost >= 0 && calculate(prodCount, prodCost, title, a_href, '', fieldParams.field_name, _inpt);

//		if(fieldParams.field_type == 'button')
//			prodCount = 0;
//continue;
		
		_inpt.addEventListener('change', () => {
			
			let count = fieldParams.field_format == 'int' ? Number.parseInt(_inpt.value) : parseFloat(_inpt.value);
			if(_inpt.type == 'checkbox')
				count = _inpt.checked && count ? count : 0;
			
			const selProd = calculate(count, prodCost, title, a_href, label, fieldParams.field_name, _inpt.type, fieldParams.article_id, _inpt);
			
console.log(selProd);
			
			if(selProd)
				_inpt.indexProd = selProd.indexProd;
			if(selProd && selProd.count == 0 || _inpt.type == 'checkbox' && _inpt.checked == false)
				removeProductLabel(selProd) || btnInfoUpdate(selectProduct.field_name,fieldParams.module_id);
		});
		
		const selProdsFields = selectsProducts.filter(
				prod => prod.title == title && prod.link == a_href && prod.label == _inpt.field_label && prod.field_name == _inpt.field_name); 
		if(selProdsFields.length != 0){
			const selProd = selProdsFields.pop();
			if(_inpt.type == 'checkbox')
				_inpt.checked = selProd.count > 0 ?	true : false;
			else
				_inpt.value = selProd.count;
			
			_inpt.indexProd = selProd.indexProd;
		}
		
		
//console.log('selectsProducts:',selectsProducts, ' /propIndex:',propIndex);
		
		
		let lblField = inputParent;
		
		
//console.log('prod.label:',selectsProducts[propIndex].field_name, ' -- fieldParams.field_name:',fieldParams.field_name);
		if(inputParent.tagName != 'LABEL'){
			lblField = document.createElement('label');
			inputParent.appendChild(lblField);
		}
		
		if(! lblField.classList.length)
			lblField.classList.add('d-inline-flex');
		
		lblField.span = null;
		_inpt.span = null;
		
		if(fieldParams.field_label_select || fieldParams.field_label_selected){
			const span = document.createElement('span');
			span.innerText = fieldParams.field_label_select;
			lblField.appendChild(span);
			lblField.span	= span;
			_inpt.span		= span;
		}
		_inpt.label = lblField;
		lblField.input = _inpt;
		
		if(fieldParams.field_type == 'button'){
			lblField.classList.add('btn');
//			lblField.insertAdjacentHTML( 'beforeend', '<span>'+fieldParams.field_label_select+'</span>' );
		}else{
			lblField.classList.add('control-group');
			lblField.classList.add('group-btns');
		}
		
		
		lblField.appendChild(_inpt);
		
		
//		if(fieldParams.field_name_value)
//		fld_cost.closest('.field-entry.' +  fieldParams.field_name_value).appendChild(lblField);
//		d-inline form-row align-items-center custom-control _col form-row form-group-inline  _form-group col-auto control-group _input-group group-btns

//continue;

		if(fieldParams.field_plusminus && fieldParams.field_type != 'button'){
			const minus = document.createElement('button');
			minus.classList.add('btn');
			minus.innerHTML = '−';// ▬ −-−▬−₋⁻
			minus.style.order = 0;
			lblField.appendChild(minus);
			
			
			let step = 1;
			if(_inpt.step)
				step = parseFloat(_inpt.step);
			
			minus.addEventListener('click', function(){
				let newVal = fieldParams.field_format == 'int' ? Number.parseInt(_inpt.value) - step : parseFloat(_inpt.value) - step;
				
				if(_inpt.min <= newVal)
					_inpt.value = newVal;
				else
					_inpt.value = 0;
				
//				parseFloat(_inpt.value) < 0 && (_inpt.value = 0);
				_inpt.dispatchEvent(new Event('change'));
			});
			
			const plus = document.createElement('button');
			plus.classList.add('btn');
			plus.innerHTML = '+';//++ 🞢 ➕🞢
			plus.style.order = 2;
			lblField.appendChild(plus);
			
			plus.addEventListener('click', function(){ 
				let newVal = fieldParams.field_format == 'int' ? Number.parseInt(_inpt.value) + step : parseFloat(_inpt.value) + step;
				
				if(! _inpt.max || _inpt.max >= newVal)
					_inpt.value = newVal;
				
				if(_inpt.min && _inpt.min > newVal)
					_inpt.value = _inpt.min;
				
				_inpt.dispatchEvent(new Event('change'));
			});
		}

		
//		fld_cost.insertAdjacentHTML('afterEnd', _inpt);
		
		
		let count = fieldParams.field_format == 'int' ? Number.parseInt(_inpt.value) : parseFloat(_inpt.value);
		if(_inpt.type == 'checkbox')
			count = _inpt.checked && count ? count : 0;
			
//		calculate(count, prodCost, title, a_href, label, fieldParams.field_name, _inpt.type, _inpt);
//		const selProd = calculate(count, cost, title, link, label, field_name, type, _inpt,  indexProd);
	
	}
	}
}
}



Init();


/* Загрузка продуктов в корзину, подсчёт суммы */
for(const prod of selectsProducts) {
let	selProd = calculate(prod.count, prod.cost, prod.title, prod.link, prod.label, prod.field_name, prod.type, prod.id, null,  prod.indexProd);

console.log(selProd);
	btnInfoUpdate(prod.field_name);
}


//window.addEventListener('storage',function(e){e.key,e.oldValue,e.newValue,e.url,e.storageArea});
window.addEventListener('storage', (e) => {
	if( ! e.key.startsWith('multiForm:calculator:'))
		return;
	
	let selProd = new selectProduct(JSON.parse(e.newValue ?? e.oldValue));
	
	if (e.newValue == null)
		selProd.count = 0
	
//	const iptIndex = fields.findIndex(fld => fld.indexProd == selProd.indexProd);
//	if(selProd.count == 0)
	
	calculate(selProd.count,selProd.cost,selProd.title,selProd.link,selProd.label,selProd.field_name, selProd.type, selProd.id, null, selProd.indexProd); //, fields[iptIndex] ?? null
});

//{"map":718,"cardpacks":[1014,949,948]}
//document.querySelector('h1').addEventListener('click',function(){
//	console.clear();
//	console.log('costTotal',costTotal);
//	console.log('selectsProducts',selectsProducts);
//	console.log('fields',fields);
//	console.log('moduleIDs',moduleIDs);
//	console.log('modulesLabelsAll',modulesLabelsAll);
//	return;
//});
});