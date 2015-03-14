<?php

final class PhabricatorOpenStackidAuthProvider
  extends PhabricatorOAuth2AuthProvider {

  public function getProviderName() {
    return pht('openstack.org');
  }

  protected function getProviderConfigurationHelp() {
    $uri = PhabricatorEnv::getProductionURI('/');
    $callback_uri = PhabricatorEnv::getURI($this->getLoginURI());

    return pht(
      "To configure OpenStackID OAuth, create a new OpenStackID ".
      "Application here:\n\n".
      "https://openstackid.org/admin/clients".
      "\n\n".
      "You should use these settings in your application:".
      "\n\n".
      "  - **URL:** Set this to your full domain with protocol. For this ".
      "    Phabricator install, the correct value is: `%s`\n".
      "  - **Redirect URL**: Set this to: `%s`\n".
      "\n\n".
      "Once you've created an application, copy the **Client ID** and ".
      "**Client Secret** into the fields above.",
      $uri,
      $callback_uri);
  }

  protected function newOAuthAdapter() {
    return new PhutilOpenStackidAuthAdapter();
  }

  protected function getLoginIcon() {
    return 'OpenStackORG';
  }
}
