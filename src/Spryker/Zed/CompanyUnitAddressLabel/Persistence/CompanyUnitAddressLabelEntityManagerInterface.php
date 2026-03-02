<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressLabelEntityManagerInterface
{
    public function saveLabelToAddressRelations(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): void;

    public function deleteRedundantLabelToAddressRelations(
        array $labelToAddressRelationIds
    ): void;
}
