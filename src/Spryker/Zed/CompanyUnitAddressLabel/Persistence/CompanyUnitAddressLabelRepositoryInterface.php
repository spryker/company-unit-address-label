<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressLabelRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabels(): CompanyUnitAddressLabelCollectionTransfer;

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function findCompanyUnitAddressLabelsByAddress(int $idCompanyUnitAddress): CompanyUnitAddressLabelCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return array<int>
     */
    public function findCompanyUnitAddressLabelIdsByAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): array;

    /**
     * @param int $idCompanyUnitAddress
     * @param array<int> $labelIds
     *
     * @return array<int>
     */
    public function findCompanyUnitAddressLabelToCompanyUnitAddressRelationIdsByAddressIdAndLabelIds(
        int $idCompanyUnitAddress,
        array $labelIds
    ): array;
}
