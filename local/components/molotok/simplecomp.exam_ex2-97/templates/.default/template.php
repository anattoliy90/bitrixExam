<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<? if(!empty($arResult["USERS"])): ?>
    <ul>
        <? foreach ($arResult["USERS"] as $user): ?>
            <li>
                [<?= $user['ID'] ?>] - <?= $user['LOGIN'] ?>
                
                <ul>
                    <? foreach ($user["NEWS"] as $news): ?>
                        <li><?= $news["NAME"] ?></li>
                    <? endforeach; ?>
                </ul>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>
