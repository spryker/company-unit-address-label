<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddress;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressLabel
 * @group Business
 * @group Facade
 * @group CompanyUnitAddressLabelFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressLabelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveLabelToAddressRelationsStoresDataToTheDatabase(): void
    {
        // Arrange
        $companyUnitAddressLabelFacade = $this->tester->getCompanyUnitAddressLabelFacade();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddressTransfer(
            $this->tester->haveCompanyBusinessUnitWithCompany()
        );

        $companyUnitAddressLabelCollectionTransfer = $this->tester->haveLabelCollection();
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $companyUnitAddressLabelFacade->saveLabelToAddressRelations($companyUnitAddressTransfer);

        // Assert
        $this->assertLabelsAreStored($companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollectionHydratesTransfer(): void
    {
        // Arrange
        $companyUnitAddressLabelFacade = $this->tester->getCompanyUnitAddressLabelFacade();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddressTransfer(
            $this->tester->haveCompanyBusinessUnitWithCompany()
        );
        $this->assertEmpty($companyUnitAddressTransfer->getLabelCollection());

        $this->tester->haveLabelAddressRelations($companyUnitAddressTransfer);

        // Act
        $companyUnitAddressTransfer = $companyUnitAddressLabelFacade->hydrateCompanyUnitAddressWithLabelCollection($companyUnitAddressTransfer);

        // Assert
        $this->assertNotEmpty($companyUnitAddressTransfer->getLabelCollection());
        $this->assertNotEmpty($companyUnitAddressTransfer->getLabelCollection()->getLabels());
        foreach ($companyUnitAddressTransfer->getLabelCollection()->getLabels() as $label) {
            $this->assertInstanceOf(SpyCompanyUnitAddressLabelEntityTransfer::class, $label);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertLabelsAreStored(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labels = (array)$companyUnitAddressTransfer->getLabelCollection()->getLabels();
        if (empty($labels)) {
            throw new Exception("No labels found in collection");
        }
        $label = $labels[0];
        $labelAddressRelation = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddressLabel(
                $label->getIdCompanyUnitAddressLabel()
            )->filterByFkCompanyUnitAddress(
                $companyUnitAddressTransfer->getIdCompanyUnitAddress()
            )->findOne();

        $this->assertInstanceOf(SpyCompanyUnitAddressLabelToCompanyUnitAddress::class, $labelAddressRelation);
    }
}
