<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

Фильтр: <a href="<?= $APPLICATION->GetCurPage(false) ?>?F=Y"><?= $APPLICATION->GetCurPage(false) ?>?F=Y</a>
<br>---

<? if (!empty($arResult['FIRMS'])): ?>
	<? foreach ($arResult['FIRMS'] as $firm): ?>
		<ul>
			<li><b><?= $firm['NAME'] ?></b></li>
			<ul>
				<?
				foreach ($firm['ITEMS'] as $item):
					$this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<li id="<?= $this->GetEditAreaId($item['ID']) ?>"><?= $item['NAME'] ?> - <?= $item['PRICE'] ?> - <?= $item['MATERIAL'] ?> - <?= $item['ARTNUMBER'] ?> (<?= $item['DETAIL_PAGE_URL'] ?>)</li>
				<? endforeach; ?>
			</ul>
		</ul>
	<? endforeach; ?>
<? endif; ?>
