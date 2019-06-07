<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressLabelBuilder;
use Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\CompanyUnitAddress\Helper\CompanyUnitAddressDataHelper;

class CompanyUnitAddressLabelDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer
     */
    public function haveCompanyUnitAddressLabel(array $seed = []): CompanyUnitAddressLabelTransfer
    {
        $companyUnitAddressLabelTransfer = (new CompanyUnitAddressLabelBuilder($seed))->build();

        $companyUnitAddressLabelEntity = (new SpyCompanyUnitAddressLabelQuery())
            ->filterByName($companyUnitAddressLabelTransfer->getName())
            ->findOneOrCreate();
        $companyUnitAddressLabelEntity->save();

        $companyUnitAddressLabelTransfer->fromArray($companyUnitAddressLabelEntity->toArray(), true);

        return $companyUnitAddressLabelTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function haveCompanyUnitAddressLabelRelations(array $seedData = []): CompanyUnitAddressResponseTransfer
    {
        if (!array_key_exists(CompanyUnitAddressTransfer::LABEL_COLLECTION, $seedData)) {
            $seedData = array_merge($seedData, [
                CompanyUnitAddressTransfer::LABEL_COLLECTION => (new CompanyUnitAddressLabelRepository())
                    ->findCompanyUnitAddressLabels(),
            ]);
        }

        $companyUnitAddressTransfer = $this->getCompanyUnitAddressHelper()->haveCompanyUnitAddress($seedData);
        $companyUnitAddressResponseTransfer = $this->getFacade()->saveLabelToAddressRelations($companyUnitAddressTransfer);

        /** @var \SprykerTest\Shared\Testify\Helper\DataCleanupHelper $dataCleanupHelper */
        $dataCleanupHelper = $this->getDataCleanupHelper();
        $dataCleanupHelper->_addCleanup(function () use ($companyUnitAddressTransfer): void {
            $companyUnitAddressTransfer->setLabelCollection();
            $this->getFacade()->saveLabelToAddressRelations($companyUnitAddressTransfer);
        });

        return $companyUnitAddressResponseTransfer;
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Zed\Country\Helper\CountryDataHelper
     */
    protected function getCompanyUnitAddressHelper(): CompanyUnitAddressDataHelper
    {
        return $this->getModule('\\' . CompanyUnitAddressDataHelper::class);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade(): CompanyUnitAddressLabelFacadeInterface
    {
        return $this->getLocator()->CompanyUnitAddressLabel()->facade();
    }
}
