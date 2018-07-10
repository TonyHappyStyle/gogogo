<?php
// @version 1.06
$cfg_cms_url = array (
  '/cms/app/admin/comm/chat_action.php' => 
  array (
    'action' => 'string',
    'color_type' => 'string',
    'color_in' => 'string',
    'chat_name' => 'string',
    'chat_item' => 'string',
    'chat_pnum' => 'string',
    'welcome' => 'string',
    'type' => 'string',
    'chatroom_ID' => 'string',
    'user_ID' => 'string',
    'usernick' => 'string',
    'tabed' => 'string',
    'tablename' => 'string',
    'the_id' => 'string',
    'RoomID' => 'string',
    'the_ip' => 'string',
    'thekey' => 'string',
  ),
  '/cms/app/admin/comm/chat_admin.php' => 
  array (
    'lever' => 'string',
    'link2' => 'string',
    'FID' => 'string',
    'chatroom_ID' => 'string',
    'pagenum' => 'string',
    'item' => 'string',
    'tablename' => 'string',
    'the_id' => 'string',
    'user_ID' => 'string',
    'usernick' => 'string',
  ),
  '/cms/app/admin/comm/chat_configinc.php' => 
  array (
    'the_page' => 'string',
    'link' => 'string',
  ),
  '/cms/app/admin/comm/chat_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/comm/forums_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/comm/home_do.php' => 
  array (
    'tabed' => 'string',
    'pUploadSize' => 'string',
    'pCounterLastIp' => 'string',
    'pCounterCookie' => 'string',
    'pTypeId' => 'int',
    'pTypeName' => 'string',
    'pTypeDesc' => 'string',
    'pUserId' => 'string',
  ),
  '/cms/app/admin/comm/home_index.php' => 
  array (
    'pUserId' => 'string',
    'pSiteName' => 'string',
  ),
  '/cms/app/admin/comm/home_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/comm/home_type_edit.php' => 
  array (
    'pTypeId' => 'int',
  ),
  '/cms/app/admin/complex/log_do.php' => 
  array (
    'pEnableLogs' => 'string',
  ),
  '/cms/app/admin/complex/log_index.php' => 
  array (
    'pModule' => 'string',
    'pUserId' => 'string',
    'pIp' => 'string',
  ),
  '/cms/app/admin/complex/site_do.php' => 
  array (
    'pSiteName' => 'string',
    'pMasterMail' => 'string',
    'pTheme' => 'string',
    'pPageWidth' => 'string',
    'pEnableForum' => 'string',
    'pEnableKicq' => 'string',
    'pEnableChat' => 'string',
    'pEnableRss' => 'string',
    'pPollCookie' => 'string',
    'pEnableOb' => 'string',
    'pEnableContribute' => 'string',
    'pXmlrpcUsername' => 'string',
    'pXmlrpcPassword' => 'string',
  ),
  '/cms/app/admin/frontpage/announce_do.php' => 
  array (
    'pAnnId' => 'int',
    'pViewType' => 'string',
    'pTitle' => 'string',
    'features' => 'string',
    //'pContent' => 'string',
  ),
  '/cms/app/admin/frontpage/announce_edit.php' => 
  array (
    'pAnnId' => 'int',
    'pViewType' => 'string',
  ),
  '/cms/app/admin/frontpage/banner_do.php' => 
  array (
    'pBannerId' => 'int',
    'pBannerUrl' => 'string',
    'pBannerAlt' => 'string',
    'pBannerActive' => 'string',
    'pBannerWidth' => 'string',
    'pBannerHeight' => 'string',
    'pOldBannerSrc' => 'string',
  ),
  '/cms/app/admin/frontpage/banner_edit.php' => 
  array (
    'pBannerId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_checker.php' => 
  array (
    'item' => 'string',
    'user_id' => 'string',
    'nickname' => 'string',
    'user_name' => 'string',
  ),
  '/cms/app/admin/frontpage/cat_edit.php' => 
  array (
    'pCatId' => 'int',
    'pCatParentId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_edit_middle.php' => 
  array (
    'pCatId' => 'int',
    'cCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_index.php' => 
  array (
    'pCatId' => 'int',
    'h_doc_flag' => 'string',
    't_doc_flag' => 'string',
  ),
  '/cms/app/admin/frontpage/cat_move.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_order.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_share.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/cat_tpl_edit.php' => 
  array (
    'pCatTplId' => 'int',
    'pCatTplType' => 'string',
  ),
  '/cms/app/admin/frontpage/content_attach.php' => 
  array (
    'pCatId' => 'int',
    'pName' => 'string',
    'pFld' => 'string',
    'pOrd' => 'string',
    'pRow' => 'string',
  ),
  '/cms/app/admin/frontpage/content_author_all.php' => 
  array (
    'pAuthorName' => 'string',
    'pCatIds' => 'arr',
    'pRow' => 'string',
    'pPg' => 'string',
  ),
  '/cms/app/admin/frontpage/content_confirm.php' => 
  array (
    'pCatId' => 'int',
    'pKeyword' => 'string',
    'pStartDate' => 'string',
    'pEndDate' => 'string',
    'pFld' => 'string',
    'pOrd' => 'string',
    'pRow' => 'string',
  ),
  '/cms/app/admin/frontpage/content_creator_all.php' => 
  array (
    'pUserId' => 'string',
    'pCatIds' => 'arr',
    'pRow' => 'string',
    'pPg' => 'string',
  ),
  '/cms/app/admin/frontpage/content_edit.php' => 
  array (
    'pCatId' => 'int',
    'pCatBodyId' => 'int',
    'pCatBodyType' => 'string',
  ),
  '/cms/app/admin/frontpage/content_icon.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/content_import_share1.php' => 
  array (
    'pCatId' => 'int',
    'pUrl' => 'string',
  ),
  '/cms/app/admin/frontpage/content_list.php' => 
  array (
    'pCatId' => 'int',
    'pKeyword' => 'string',
    'pStartDate' => 'string',
    'pEndDate' => 'string',
    'pUserId' => 'string',
    'pUserName' => 'string',
    'pFld' => 'string',
    'pOrd' => 'string',
    'pRow' => 'string',
  ),
  '/cms/app/admin/frontpage/content_move.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/content_note.php' => 
  array (
    'pCatId' => 'int',
    'pCatBodyId' => 'int',
    'pRow' => 'string',
  ),
  '/cms/app/admin/frontpage/content_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/frontpage/count_list.php' => 
  array (
    'pCatId' => 'int',
    'pRow' => 'string',
    'pPg' => 'string',
  ),
  '/cms/app/admin/frontpage/count_main.php' => 
  array (
    'pType' => 'string',
  ),
  '/cms/app/admin/frontpage/count_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/frontpage/link_add.php' => 
  array (
    'pLinkTypeId' => 'int',
  ),
  '/cms/app/admin/frontpage/link_do.php' => 
  array (
    'pLinkId' => 'int',
    'pLinkName' => 'string',
    'pLinkLocation' => 'string',
    'pLinkTypeId' => 'int',
    'pOldLinkTypeId' => 'int',
  ),
  '/cms/app/admin/frontpage/link_edit.php' => 
  array (
    'pLinkTypeId' => 'int',
    'pLinkId' => 'int',
  ),
  '/cms/app/admin/frontpage/link_new.php' => 
  array (
    'pLinkTypeId' => 'int',
  ),
  '/cms/app/admin/frontpage/link_top.php' => 
  array (
    'tabed' => 'string',
  ),
  '/cms/app/admin/frontpage/link_type_do.php' => 
  array (
    'pLinkTypeName' => 'string',
    'pLinkTypeId' => 'int',
    'pLinkId' => 'int',
  ),
  '/cms/app/admin/frontpage/link_type_edit.php' => 
  array (
    'pLinkTypeId' => 'int',
  ),
  '/cms/app/admin/frontpage/logo_do.php' => 
  array (
    'pLogoAlt' => 'string',
    'pLogoWidth' => 'string',
    'pLogoHeight' => 'string',
  ),
  '/cms/app/admin/frontpage/menu_do.php' => 
  array (
    'pSubmenuName' => 'string',
    'pSubmenuUrl' => 'string',
    'pMenuName' => 'string',
    'pIsParent' => 'string',
    'pIsNewwin' => 'string',
    'pPageMenuId' => 'int',
    'pOrderStr' => 'string',
    'goto_menu_id' => 'int',
    'old_act' => 'string',
  ),
  '/cms/app/admin/frontpage/menu_edit.php' => 
  array (
    'pPageMenuId' => 'int',
  ),
  '/cms/app/admin/frontpage/menu_index.php' => 
  array (
    'pPageMenuId' => 'int',
  ),
  '/cms/app/admin/frontpage/menu_new.php' => 
  array (
    'pPageMenuId' => 'int',
  ),
  '/cms/app/admin/frontpage/menu_order.php' => 
  array (
    'pPageMenuId' => 'int',
  ),
  '/cms/app/admin/frontpage/news_do.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/frontpage/pic_do.php' => 
  array (
    'pPicAlt' => 'string',
    'pPicWidth' => 'string',
    'pPicHeight' => 'string',
    'pPicTitle' => 'string',
    'pPicLink' => 'string',
    'pPicDsc' => 'string',
    'pPicAlign' => 'string',
  ),
  '/cms/app/admin/frontpage/poll_do.php' => 
  array (
    'pPollId' => 'int',
    'pPollType' => 'string',
    'pPollTitle' => 'string',
    'items' => 'arr',
  ),
  '/cms/app/admin/frontpage/poll_edit.php' => 
  array (
    'pPollId' => 'int',
    'pPollType' => 'string',
    'pItemCount' => 'int',
  ),
  '/cms/app/admin/include/functioninc.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/admin/index_main.php' => 
  array (
    'tabed' => 'string',
    'rows' => 'int',
  ),
  '/cms/app/admin/usr/group_do.php' => 
  array (
    'pSizeId' => 'int',
    'pSizeTitle' => 'string',
    'pHomeSize' => 'int',
  ),
  '/cms/app/admin/usr/group_edit.php' => 
  array (
    'pSizeId' => 'int',
  ),
  '/cms/app/admin/usr/user_access.php' => 
  array (
    'pUsers' => 'arr',
  ),
  '/cms/app/admin/usr/user_access_content.php' => 
  array (
    'pUsers' => 'arr',
    'pUserId' => 'arr',
  ),
  '/cms/app/admin/usr/user_do.php' => 
  array (
    'pSizeId' => 'int',
    'pWhere' => 'arr',
    'pUserId' => 'arr',
    'pAccessIds' => 'arr',
    'pUsers' => 'arr',
    'pDenyFlag' => 'string',
    'pOffset1' => 'string',
    'pOffset2' => 'string',
    'pNum' => 'int',
    'pCount2' => 'string',
  ),
  '/cms/app/admin/usr/user_group.php' => 
  array (
    'pUsers' => 'arr',
  ),
  '/cms/app/admin/usr/user_index.php' => 
  array (
    'per' => 'string',
    'byf' => 'string',
    'bya' => 'string',
    's_user_id' => 'string',
    's_nickname' => 'string',
    's_size_id' => 'string',
    's_admin' => 'string',
  ),
  '/cms/app/chat/ac.php' => 
  array (
    'mStartTime' => 'string',
    'mColor_show' => 'string',
    'usernick' => 'string',
    'mRoomID' => 'string',
    'pMax_old' => 'string',
  ),
  '/cms/app/chat/alert.php' => 
  array (
    'info' => 'string',
  ),
  '/cms/app/chat/chat.php' => 
  array (
    'mRoomID' => 'string',
  ),
  '/cms/app/chat/chat_action.php' => 
  array (
    'chat_name' => 'string',
    'chat_item' => 'string',
    'chat_pnum' => 'string',
    'welcome' => 'string',
  ),
  '/cms/app/chat/command.php' => 
  array (
    'mRoomID' => 'string',
    'pUsernick' => 'string',
    'action' => 'string',
    'tell_all' => 'string',
  ),
  '/cms/app/chat/filtrate_ac.php' => 
  array (
    'mRoomID' => 'string',
    'mAction' => 'string',
    'mFiltrateID' => 'string',
  ),
  '/cms/app/chat/include/get_url.php' => 
  array (
    'mRoomID' => 'string',
    'mAim' => 'string',
    'mAimid' => 'string',
    'usernick' => 'string',
    'sUserId' => 'string',
    'mColor_top' => 'string',
    'mColor_say' => 'string',
    'mColor_show' => 'string',
    'mColor_list' => 'string',
    'mMaster' => 'string',
    'mMasternick' => 'string',
    'mChat_name' => 'string',
    'clearshow' => 'string',
    'Thefirst' => 'string',
    'mStartTime' => 'string',
  ),
  '/cms/app/chat/list.php' => 
  array (
    'mRoomID' => 'string',
    'mColor_list' => 'string',
  ),
  '/cms/app/chat/list_chat.php' => 
  array (
    'mRoomID' => 'string',
    'mColor_list' => 'string',
  ),
  '/cms/app/chat/list_filtrate.php' => 
  array (
    'mRoomID' => 'string',
  ),
  '/cms/app/chat/modify.php' => 
  array (
    'mStartTime' => 'string',
    'mRoomID' => 'string',
    'mMaster' => 'string',
    'mMasternick' => 'string',
    'mChat_name' => 'string',
    'newnick' => 'string',
    'head' => 'string',
    'mColor_top' => 'string',
    'mColor_say' => 'string',
    'mColor_show' => 'string',
    'mColor_list' => 'string',
    'aim' => 'string',
    'aimid' => 'string',
  ),
  '/cms/app/chat/say.php' => 
  array (
    'sUserId' => 'string',
    'mAim' => 'string',
    'mAimid' => 'string',
  ),
  '/cms/app/chat/speak.php' => 
  array (
    'mAction' => 'string',
    'Thefirst' => 'string',
    'pMsg' => 'string',
    'usernick' => 'string',
    'pAimid' => 'string',
    'pAim' => 'string',
    'pFace' => 'string',
    'pQuiet' => 'string',
    'pSaycolor' => 'string',
    'mOutput' => 'string',
    'mStartTime' => 'string',
    'mRoomID' => 'string',
    'mColor_top' => 'string',
    'mColor_say' => 'string',
    'mColor_show' => 'string',
    'mColor_list' => 'string',
    'mMaster' => 'string',
    'mMasternick' => 'string',
    'mChat_name' => 'string',
  ),
  '/cms/app/chat/tell_all.php' => 
  array (
    'mRoomID' => 'string',
  ),
  '/cms/app/forum/editpost.php' => 
  array (
    'pPostId' => 'int',
    'pTopicId' => 'int',
    'pForumId' => 'int',
    'pTopicStart' => 'string',
    'pBefore' => 'string',
    'pPostStart' => 'string',
    'pDelete' => 'string',
    'pCancel' => 'string',
    'pSubmit' => 'string',
    'pEmbed' => 'string',
    'pTitle' => 'string',
    'pBody' => 'string',
    'pHtml' => 'string',
    'pBbCode' => 'string',
    'pSmile' => 'string',
    'pSig' => 'string',
    'pl_id' => 'int',
    'pl_del' => 'string',
  ),
  '/cms/app/forum/index.php' => 
  array (
    'pViewCat' => 'string',
  ),
  '/cms/app/forum/mklist.php' => 
  array (
    'tp' => 'string',
    'opt' => 'string',
    'del' => 'string',
    'opt_list' => 'string',
    'go' => 'string',
  ),
  '/cms/app/forum/newtopic.php' => 
  array (
    'pForumId' => 'int',
    'pTopicStart' => 'string',
    'pBefore' => 'string',
    'pCancel' => 'string',
    'pSubmit' => 'string',
    'pEmbed' => 'string',
    'pTitle' => 'string',
    'pBody' => 'string',
    'pHtml' => 'string',
    'pBbCode' => 'string',
    'pSmile' => 'string',
    'pSig' => 'string',
    'pl_id' => 'int',
    'pl_del' => 'string',
  ),
  '/cms/app/forum/poll.php' => 
  array (
    'pl_id' => 'int',
    'frm_id' => 'int',
    'pl_name' => 'string',
    'pl_max_votes' => 'arr',
    'pl_expiry_date' => 'string',
    'pl_option' => 'string',
    'pl_optedit' => 'string',
    'del_id' => 'int',
    'pl_submit' => 'string',
    'pl_upd' => 'string',
    'pl_add' => 'string',
    'pl_opt_id' => 'int',
  ),
  '/cms/app/forum/reply.php' => 
  array (
    'pTopicId' => 'int',
    'pForumId' => 'int',
    'pTopicStart' => 'string',
    'pBefore' => 'string',
    'pPostStart' => 'string',
    'pCancel' => 'string',
    'pSubmit' => 'string',
    'pEmbed' => 'string',
    'pTitle' => 'string',
    'pBody' => 'string',
    'pHtml' => 'string',
    'pBbCode' => 'string',
    'pSmile' => 'string',
    'pSig' => 'string',
    'pQuote' => 'string',
    'pPostId' => 'int',
    'pl_id' => 'int',
    'pl_del' => 'string',
  ),
  '/cms/app/forum/search.php' => 
  array (
    'submit' => 'string',
    'pStart' => 'string',
  ),
  '/cms/app/forum/topicadmin.php' => 
  array (
    'pPostId' => 'int',
    'pTopicId' => 'int',
    'pForumId' => 'int',
    'pTopicStart' => 'string',
    'pBefore' => 'string',
    'pPostStart' => 'string',
    'pMode' => 'string',
    'pSubmit' => 'string',
    'pNewForumId' => 'int',
  ),
  '/cms/app/forum/viewforum.php' => 
  array (
    'pForumId' => 'int',
    'pTopicStart' => 'string',
    'pBefore' => 'string',
  ),
  '/cms/app/forum/viewtopic.php' => 
  array (
    'pForumId' => 'int',
    'pTopicId' => 'int',
    'pGoto' => 'string',
    'pTopicStart' => 'string',
    'pPostStart' => 'string',
    'pBefore' => 'string',
    'pl_view' => 'string',
  ),
  '/cms/app/home/counter.php' => 
  array (
    'df' => 'string',
    'ft' => 'string',
    'frgb' => 'string',
    'trgb' => 'string',
    'dd' => 'string',
    'display' => 'string',
  ),
  '/cms/app/home/counter_info.php' => 
  array (
    'pUserId' => 'string',
  ),
  '/cms/app/home/do.php' => 
  array (
    'pCurDir' => 'string',
    'pName' => 'string',
    'pContent' => 'string',
    'pNewName' => 'string',
    'pCover' => 'string',
    'pUnzip' => 'string',
    'pCounterSortName' => 'string',
    'pCounterSortUrl' => 'string',
    'pCounterTypeId' => 'int',
    'pCounterSortDesc' => 'string',
    'pGbookName' => 'string',
    'pGbookFontColor' => 'string',
    'pUserId' => 'string',
    'pMessage' => 'string',
    'pMessageName' => 'string',
    'pMessageMail' => 'string',
    'pMessageId' => 'string',
  ),
  '/cms/app/home/edit.php' => 
  array (
    'pName' => 'string',
    'pCurDir' => 'string',
    'pModule' => 'string',
  ),
  '/cms/app/home/editor/Inc/insimage.php' => 
  array (
    'pCurDir' => 'string',
    'pMode' => 'string',
  ),
  '/cms/app/home/file.php' => 
  array (
    'pCurDir' => 'string',
  ),
  '/cms/app/home/gbook.php' => 
  array (
    'pUserId' => 'string',
    'msg_search' => 'string',
    'pPage' => 'int',
    'pGbookId' => 'int',
    'pAct' => 'string',
    'checked' => 'int',
    'pMessage_rpl' => 'string',
    'rpl_id' => 'int',
  ),
  '/cms/app/home/new.php' => 
  array (
    'pName' => 'string',
    'pCurDir' => 'string',
  ),
  '/cms/app/home/register.php' => 
  array (
    'pRegistered' => 'string',
  ),
  '/cms/app/home/sort.php' => 
  array (
    'pSort' => 'string',
    'pOrder' => 'string',
  ),
  '/cms/app/home/upload.php' => 
  array (
    'pCurDir' => 'string',
  ),
  '/cms/app/info/cat/index.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/info/doc/down.php' => 
  array (
    'pAttachId' => 'int',
  ),
  '/cms/app/info/doc/index.php' => 
  array (
    'pCatBodyId' => 'int',
    'pRun' => 'string',
    'pCatId' => 'int',
  ),
  '/cms/app/info/doc/note.php' => 
  array (
    'pCatBodyId' => 'int',
    'pName' => 'string',
    'pEmail' => 'string',
    'pContent' => 'string',
  ),
  '/cms/app/info/doc/submit1.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/info/doc/submit2.php' => 
  array (
    'pCatId' => 'int',
  ),
  '/cms/app/info/doc/submit_do.php' => 
  array (
    'cat_id' => 'int',
    'pCatBodyTitle' => 'string',
    'pCatBodyKeyword' => 'string',
    'pCatBodyContent' => 'arr',
  ),
  '/cms/app/info/poll/do.php' => 
  array (
    'pPollId' => 'int',
    'pPollItemIds' => 'arr',
  ),
  '/cms/app/info/poll/vote.php' => 
  array (
    'pPollId' => 'int',
  ),
  '/cms/app/info/search/index.php' => 
  array (
    'pKeyword' => 'string',
    'pStartDate' => 'string',
    'pEndDate' => 'string',
    'searchtype' => 'string',
  ),
  '/cms/app/info/subscribe/do.php' => 
  array (
    'pCatIds' => 'arr',
  ),
  '/cms/app/info/subscribe/index.php' => 
  array (
    'modify' => 'string',
  ),
  '/cms/app/js/menujs.php' => 
  array (
    'pPath' => 'string',
  ),
  '/cms/app/kicq/commit.php' => 
  array (
    'u' => 'string',
    'to' => 'string',
    'body' => 'string',
    'act' => 'string',
  ),
  '/cms/app/kicq/loginbp.php' => 
  array (
    'purl' => 'string',
  ),
  '/cms/app/kicq/message.php' => 
  array (
    'num' => 'string',
  ),
  '/cms/app/kicq/searchlist.php' => 
  array (
    'online' => 'string',
    'uid' => 'string',
    'nickname' => 'string',
  ),
  '/cms/app/user/do.php' => 
  array (
    'sbm_face' => 'string',
    'face' => 'string',
    'sbm_up_face' => 'string',
    'sbm_sign' => 'string',
    'signature' => 'string',
  ),
  '/cms/app/home/mgbook.php' => 
  array (
    'pPage' => 'int',
  ),
  '/cms/app/info/qinfo/qschool.php' => 
  array (
    'pPage' => 'int',
    'pSchoolName' => 'string',
    'pSchoolSystem' => 'string',
  ),
  '/cms/app/js/menu.js.php' => 
  array (
    'pPath' => 'string',
  ),
  '/cms/app/service/aggregation_new.php' => 
  array (
    'pPage' => 'int',
    'pDateFormat' => 'string',
    'pAggId' => 'int',
    'pAct' => 'string',
    'pContentType' => 'string',
    'pTitleLength' => 'int',
    'pAppendText' => 'string',
    'pRsRows' => 'int',
    'pAggUrl' => 'string',
  ),
  '/cms/app/service/aggregation_backend.php' => 
  array (
    'pAggId' => 'int',
    'pAct' => 'string',
    'pAggUrl' => 'string',
  ),
  '/cms/app/info/aggregation/index.php' => 
  array (
    'pPage' => 'int',
    'pDateFormat' => 'string',
    'pAggId' => 'int',
    'pAct' => 'string',
    'pContentType' => 'string',
    'pTitleLength' => 'int',
    'pAppendText' => 'string',
    'pRsRows' => 'int',
    'pAggUrl' => 'string',
  ),
  '/cms/app/service/report.php' => 
  array (
    'go_time_e' => 'string',
    'go_time_s' => 'string',
  ),
  '/cms/app/rcjl/gwxq_index.php' => 
  array (
    's_joblx' => 'int',
  ),
  '/cms/app/rcjl/rctj_index.php' => 
  array (
    's_local' => 'string',
    's_joblx' => 'string',
  ),
  '/cms/app/service/mail.php' => 
  array (
    'id' => 'int',
    's_joblx' => 'string',
  ),
);

function cms_remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x19])/', '', $val);
    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
        
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }
    // 替换敏感单词及特殊字符
    $replace = array(
        '/select/i' => '', 
        '/update/i' => '', 
        '/insert/i' => '', 
        '/union/i'  => '', 
        '/xp_cmdshell/i' => '', 
        '/\band\b/i' => '', 
        '/\bor\b/i' => '', 
        '/>/i' => '》', 
        '/</i' => '《', 
        //'=' => '＝', 
        '/"/i' => '“', 
        '/%/i' => '％', 
        //'/' => '／', 
        '/\\\\/i' => '、', 
        "/'/i" => '‘', 
        '/\(/i' => '（', 
        '/\)/i' => '）',
        '/script/i' => '', 
        '/iframe/i' => '',
        '/src/i' => '', 
        '/window\.open/i' => '',
        '/onmouseover/i' => '');
    $val = preg_replace(array_keys($replace), array_values($replace), $val);
    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    
    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'_'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

function cms_strip_tags($var) {
    if (!is_string($var)) return $var;
    return preg_replace('#</?[a-z][^>]*>#i', '', $var);
}

/**
 * 参数过滤。
 *
 * @param mixed $val 需要过滤的参数变量。
 * @param boolean $filter_all 是否所有的参数都过滤，默认为 false，只过滤 $cfg_cms_url 中定义的参数列表。
 * @return mixed 返回过滤之后的参数列表。
 */
function cms_filter($val, $filter_all = false) {
    global $cms, $cfg_cms_url, $now_url;
    
    if (is_array($val)) {
        if ($filter_all) {
            foreach ($val as $k => $v) {
                $val[$k] = cms_filter($v, $filter_all);
            }
        } else if (isset($cfg_cms_url[$now_url])) {
            $args = $cfg_cms_url[$now_url];
            foreach ($val as $k => $v) {
                if (isset($args[$k])) {
                    if ($args[$k] == 'int') {
                        $val[$k] = intval($v);
                    } else {
                        $val[$k] = cms_filter($v);
                    }
                }
            }
        }
    } else {
        $conn =& $cms->get_adodb_conn();
        $val = cms_strip_tags($val);
        $val = cms_remove_xss($val);
        $val = substr($conn->qstr($val), 1, -1);
        $val = str_replace(array('\r', '\n'), array("\r", "\n"), $val);
    }
    return $val;
}

/**
 * 获取用于防止 CSRF 攻击的表单令牌。
 *
 * @param string $form_id 表单 ID。
 * @param integer $expires 令牌过期时间，单位为秒。
 * @return string 返回令牌字符串。
 */
function cms_form_token($form_id, $expires = 1800)
{
    if (empty($_SESSION['token'][$form_id])) {
        $token = md5($form_id.uniqid(rand(), true));
        $_SESSION['token'][$form_id] = $token;
    } else {
        $token = $_SESSION['token'][$form_id];
    }
    $_SESSION['token_time'][$form_id] = time() + $expires;
    return $token;
}

/**
 * 校验防 CSRF 攻击的表单令牌是否正确，不正确则输出错误信息，结束程序。
 *
 * @param string $form_id 表单 ID。
 * @return void
 */
function cms_check_token($form_id)
{
    $output = '<html>
<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <title>表单令牌校验失败</title>
</head>

<body>
<div style="width:600px; margin:30px auto; font-size:14px; font-family:Tahoma; color:black; background-color:#F9F2A7; border:2px solid red; padding:8px;">
    <font color="red">表单令牌校验失败：%s。</font><br/>
    出于安全考虑，网站中的表单提交均需要校验令牌，以防止跨站脚本攻击，若有疑问请联系管理员。
    <a href="'.($_SERVER['HTTP_REFERER'] ? 'javascript:history.go(-1);void(0)' : '/cms/').'"><font color="blue">请点击此处返回重试</font></a>。
</div>
</body>
</html>';
    if (empty($_SESSION['token'][$form_id]) || empty($_SESSION['token_time'][$form_id])) die(sprintf($output, '未设置令牌及令牌过期时间'));
    $token = @$_REQUEST['token'];
    if ($_SESSION['token'][$form_id] != $token) die(sprintf($output, '令牌错误'));
    if (time() > $_SESSION['token_time'][$form_id]) die(sprintf($output, '令牌超时'));
    //unset($_SESSION['token'][$form_id], $_SESSION['token_time'][$form_id]);
}

$now_url = $_SERVER["SCRIPT_NAME"];

$_GET     = cms_filter($_GET);
$_POST    = cms_filter($_POST);
$_REQUEST = cms_filter($_REQUEST);

//单独判断 url 为 path_info 的情况
$cfg_cms_pathinfo = array(
    $cms->app_url.'/info/doc/index.php',
    $cms->app_url.'/info/cat/index.php',
    $cms->app_url.'/info/rss/index.php',
    $cms->app_url.'/info/chn/index.php',
    $cms->app_url.'/info/aggregation/index.php');
if (!empty($_SERVER['PATH_INFO']) && in_array($now_url, $cfg_cms_pathinfo)){
    $params = explode('/', preg_replace('/\?.+$/', '', $_SERVER['PATH_INFO']));
    foreach ($params as $pk => $pv) {
        if ($pk ==1) {
            $params[$pk] = intval($pv);
        } else {
            $params[$pk] = cms_filter($pv);
        }
    }
    $_SERVER['PATH_INFO'] = implode('/', $params);
}
