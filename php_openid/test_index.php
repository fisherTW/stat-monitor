<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <title>開發測試RP</title>
  </head>
  <body ng-app="openidApp" ng-controller="openidCtrl">
    <h1>OpenID OP驗證測試</h1>
    <p>
      這個驗證測試的機制採用 <a href="http://github.com/openid/php-openid">PHP OpenID</a> library.
    </p>
	<div class="container">
    <div id="verify-form">
      <form method="get" action="test_openid.php">
        <input type="hidden" name="action" value="verify" />
        <input type="hidden" type="text" name="openid_identifier" value="http://openid.kh.edu.tw" />
        <input type="submit" value="高雄市 OpenID認證" />
      </form><br>
    </div>
	</div>
  </body>
</html>