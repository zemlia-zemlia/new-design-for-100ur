<?php
/**
 * CActiveTreeRecord class file.
 *
 * @author Remko Nolten <remko@nolten.nu>
 *
 * @see http://www.yiiframework.com/
 *
 * @copyright Copyright &copy; 2008-2009 Remko Nolten
 */

/**
 * Behavior Class that allows to store hierarchical data using the {@link CActiveRecord} class.
 * It uses the Modified Pre-order Traversal algorithm, as explained on: http://www.sitepoint.com/article/hierarchical-data-database.
 *
 * When you have added this behavior to your ActiveRecord model, you can use it as any other ActiveRecord class,
 * but you need to add some extra fields to your database table:
 * <ul>
 *  <li><strong>id</strong>: The unique identifier of the row. (preferably the primary key)</li>
 *  <li><strong>lft</strong>: This column needs to be of the integer type, and it wouldn't hurt to create an index on it.</li>
 *  <li><strong>rgt</strong>: This column also needs to be of the integer type, and again, it wouldn't hurt to create an index on it.</li>
 *  <li><strong>level</strong>: This column also needs to be of the integer type, and stores the depth of the node.</li>
 * </ul>
 *
 * You can use different column names for the above columns, just set the _idCol, _lftCol, _rgtCol and _lvlCol parameters when attaching this behavior.
 *
 * Note that the ActiveRecord->delete() method is disabled because it will destroy the structure of your tree.
 *
 * @author Remko Nolten <remko@nolten.nu>
 */
include dirname(__FILE__) . '/' . 'CNestedSetBehavior.php';
class TreeBehavior extends CNestedSetBehavior
{
    /**
     * The name of the column which is used to store the unique identifier.
     */
    public $_idCol = 'id';

    /**
     * The name of the column which is used to store left tree value.
     */
    public $_lftCol = 'lft';

    /**
     * The name of the column which is used to store right tree value.
     */
    public $_rgtCol = 'rgt';

    /**
     * The name of the column which is used to store depth of the node.
     */
    public $_lvlCol = 'level';

    /**
     * Static array that keeps track of all models with TreeBehaviour attacked.
     */
    private static $_registered = [];

    /**
     * Returns the index of the node in the register.
     *
     * @param $node The node to search for
     *
     * @return The index of the node in the register or -1 when not found
     */
    private static function getRegisterIndex($node)
    {
        foreach (self::$_registered as $key => $n) {
            if ($n === $node) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * Registers a node so that it can be dynamicly updated and it doesn't need to be reloaded from the databse every time someone changes the tree structure.
     *
     * @param $node The node to register
     */
    protected static function register($node)
    {
        if (!self::isRegistered($node)) {
            self::$_registered[] = $node;
        }
    }

    /**
     * Unregister a node.
     *
     * @param the node to unregister
     */
    protected static function unregister($node)
    {
        unset(self::$_registered[self::getRegisterIndex($node)]);
    }

    /**
     * Returns the array with all registered node objects.
     *
     * @return array A array with all objects that have TreeBehavior attached
     */
    public static function getRegisteredNodes()
    {
        return self::$_registered;
    }

    /**
     * Checks if a node is registered.
     *
     * @param $node the node to check
     *
     * @return bool true when registered, false otherwise
     */
    protected static function isRegistered($node)
    {
        return -1 !== self::getRegisterIndex($node);
    }

    /**
     * Update all node left/right values that are in the specified range by adding the adjustment value.
     * Optionally, it can also increase/decrease the level of the node.
     *
     * For example: TreeBehavior::updateRegisteredNodeRange(2,5,6) will increase all left values in the register that are 5 or higher with 2 and increases all right values that are 6 or higher with 2.
     *
     * @param $class The classname of the model (so that we can differentiate multiple tree tables)
     * @param $adjustment the value which will be added to the left/right values of all nodes in the range
     * @param $minLeft The lowest left value we need to update. (-1 for no left lower limit)
     * @param $minRight The lowest right value we need to update. (-1 for no right lower limit)
     * @param $maxLeft The highest left value we need to update. (-1 for no left upper limit)
     * @param $maxRight The highest right value we need to update. (-1 for no right upper limit)
     * @param $levelAdjustment the value that will be added to the level for all nodes in the range
     */
    protected static function updateRegisteredNodeRange($class, $adjustment, $minLeft = -1, $minRight = 1, $maxLeft = -1, $maxRight = -1, $levelAdjustment = 0)
    {
        foreach (self::$_registered as $node) {
            if (get_class($node) != $class) {
                continue;
            }
            $lft = $node->getLeftValue();
            $rgt = $node->getRightValue();
            if (null == $lft && null == $rgt) {
                continue;
            }
            if (($lft >= $minLeft || -1 == $minLeft) && ($lft <= $maxLeft || -1 == $maxLeft)) {
                $node->setLeftValue($lft + $adjustment);
            }
            if (($rgt <= $maxRight || -1 == $maxRight) && ($rgt >= $minRight || -1 == $minRight)) {
                $node->setRightValue($rgt + $adjustment);
            }
            if (($lft >= $minLeft || -1 == $minLeft) && ($lft <= $maxLeft || -1 == $maxLeft)
               && ($rgt >= $maxRight || -1 == $maxRight) && ($rgt <= $maxRight || -1 == $minRight) && 0 != $levelAdjustment) {
                $node->setLevelValue($node->getLevelValue() + $levelAdjustment);
            }
        }
    }

    /**
     * Updates all nodes in the register that have a unique identifier that are in the array with the given adjustments.
     * Example: TreeBehavior::updateRegisteredNodes([1,2,3], -2, 1); Decreases the left/right values of the nodes with ID 1, 2 and 3 with 2 and increases the level with 1.
     *
     * @param $class The classname of the model (so that we can differentiate multiple tree tables)
     * @param $ids The list of unique identifiers
     * @param $adjustment the value which will be added to the left/right values of all nodes in the range
     * @param $levelAdjustment the value that will be added to the level for all nodes in the range
     */
    protected static function updateRegisteredNodes($class, array $ids, $adjustment, $levelAdjustment)
    {
        foreach (self::$_registered as $movenode) {
            if (get_class($movenode) != $class) {
                continue;
            }
            if (in_array($movenode->getIDValue(), $ids)) {
                $movenode->setLeftValue($movenode->getLeftValue() + $adjustment);
                $movenode->setRightValue($movenode->getRightValue() + $adjustment);
                $movenode->setLevelValue($movenode->getLevelValue() + $levelAdjustment);
            }
        }
    }

    /**
     * Makes sure the object unregisters itself.
     */
    public function __destruct()
    {
        self::unregister($this);
    }

    /**
     * Function (event handler) that disables the default delete function of the ActiveRecord class.
     *
     * @param $event information of the event that is dispatched
     */
    public function beforeDelete($event)
    {
        throw new TreeException('Do not use the delete() function when a TreeBehavior is attached. Use deleteNode() instead.');
    }

    /**
     * Makes sure every new node is registered after it iss created so that its structural values can be dynamicly adjusted.
     * (Override from {@link CActiveRecordBehavior::afterConstruct}).
     */
    public function afterConstruct($event)
    {
        self::register($event->sender);
    }

    /**
     * Makes sure every new node is registered after it is created so that its structural values can be dynamicly adjusted.
     * (Override from {@link CActiveRecordBehavior::afterFind}).
     */
    public function afterFind($event)
    {
        self::register($event->sender);
    }

    /**
     *  Get the value stored in the "lft" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *
     *  @return The left value for this node, or null when unknown
     */
    public function getLeftValue()
    {
        return $this->Owner->attributes[$this->_lftCol];
    }

    /**
     *  Get the value stored in the "rgt" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *
     *  @return The right value for this node, or null when unknown
     */
    public function getRightValue()
    {
        return $this->Owner->attributes[$this->_rgtCol];
    }

    /**
     *  Get the value stored in the "id" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *
     *  @return the ID value for this node, or null when unknown
     */
    protected function getIDValue()
    {
        return $this->Owner->attributes[$this->_idCol];
    }

    /**
     *  Get the value stored in the "level" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *
     *  @return The level value for this node, or null when unknown
     */
    protected function getLevelValue()
    {
        return $this->Owner->attributes[$this->_lvlCol];
    }

    /**
     *  Set the value in the "lft" column for this node.
     *  DO NOT USE THIS FUNCTION UNTIL YOU KNOW EXACTLY WHAT YOU ARE DOING
     *  Setting wrong values can destroy your hierarchical tree and may be difficult to recover.
     *  The Left/Right/ID/Level values are automatically set when you use the special tree manipulation functions in this class
     *  so most of the time you won't need this function.
     *  The use of this function must be made permanent with the {@link CActiveRecord.save} function.
     *
     *  @param $val the new value you want to set
     *
     *  @return The new value
     */
    protected function setLeftValue($val)
    {
        $col = $this->_lftCol;

        return $this->Owner->$col = $val;
    }

    /**
     *  Set the value in the "rgt" column for this node.
     *  DO NOT USE THIS FUNCTION UNTIL YOU KNOW EXACTLY WHAT YOU ARE DOING
     *  Setting wrong values can destroy your hierarchical tree and may be difficult to recover.
     *  The Left/Right/ID/Level values are automatically set when you use the special tree manipulation functions in this class
     *  so most of the time you won't need this function.
     *  The use of this function must be made permanent with the {@link CActiveRecord.save} function.
     *
     *  @param $val the new value you want to set
     *
     *  @return The new value
     */
    protected function setRightValue($val)
    {
        $col = $this->_rgtCol;

        return $this->Owner->$col = $val;
    }

    /**
     *  Set the value in the "ID" column for this node.
     *  The Left/Right/ID/Level values are automatically set when you use the special tree manipulation functions in this class
     *  so most of the time you won't need this function.
     *  The use of this function must be made permanent with the {@link CActiveRecord.save} function.
     *
     *  @param $val the new value you want to set
     *
     *  @return The new value
     */
    protected function setIDValue($val)
    {
        $col = $this->_idCol;

        return $this->Owner->$col = $val;
    }

    /**
     *  Set the value in the "level" column for this node.
     *  DO NOT USE THIS FUNCTION UNTIL YOU KNOW EXACTLY WHAT YOU ARE DOING
     *  Setting wrong values can destroy your hierarchical tree and may be difficult to recover.
     *  The Left/Right/ID/Level values are automatically set when you use the special tree manipulation functions in this class
     *  so most of the time you won't need this function.
     *  The use of this function must be made permanent with the {@link CActiveRecord.save} function.
     *
     *  @param $val the new value you want to set
     *
     *  @return The new value
     */
    protected function setLevelValue($val)
    {
        $col = $this->_lvlCol;

        return $this->Owner->$col = $val;
    }

    /**
     * Returns the childnodes of this node in an array.
     * This function does not return the children of the child nodes (i.e. grandchildren).
     *
     * @return array() An array with the child nodes or an empty array when no child nodes are found
     */
    public function getChildNodes()
    {
        Yii::trace(get_class($this) . '.getChildNodes()', 'extensions.nestedset.treebehavior.getChildNodes()');
        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t retrieve the child nodes of a parent that is not yet in the database. Add the parent node to the tree first using AppendChild or an Insert* method.');
        }
        $table = $this->Owner->tableName();
        $lft = $this->getLeftValue();
        $rgt = $this->getRightValue();
        $level = $this->getLevelValue();

        $builder = $this->Owner->getCommandBuilder();
        $criteria = $builder->createCriteria($this->_lftCol . ' > ? AND ' . $this->_rgtCol . ' < ? AND ' . $this->_lvlCol . ' = ?', [$lft, $rgt, $level + 1]);
        $criteria->order = $this->_lftCol . ' ASC';
        $command = $builder->createFindCommand($this->Owner->getTableSchema(), $criteria);
        $res = $this->Owner->populateRecords($command->queryAll());

        return is_array($res) ? $res : [];
    }

    /**
     * Returns the depth of the current node in the complete tree.
     *
     * @return int the depth of the node, where the root node is always at depth 0
     */
    public function getDepth()
    {
        return $this->getLevelValue();
    }

    /**
     * Returns false when the node is a leaf node (i.e. has no child nodes).
     *
     * @return bool true when the node has childe nodes, false when the node is a leaf
     */
    public function hasChildNodes()
    {
        return $this->getLeftValue() != ($this->getRightValue() - 1);
    }

    /**
     * Inserts this node as the last child of the given node.
     * if $brother is specified then this node will be inserted before
     * it's brother. When $node is not a new node (but is already in the database),
     * this methods moves the nodes to the new position.
     *
     * @param CActiveTreeRecord $node    the node you want to append to this node
     * @param CActiveTreeRecord $brother the node before which you want to insert this node
     *
     * @return bool true on succes or False on failure
     */
    public function appendChild($node, $brother = null)
    {
        // Fetch nodes information
        $parent = $this;
        $transaction = $this->Owner->dbConnection->beginTransaction();
        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t append a node to a parent that is not yet in the database. Add the parent node to the tree first using AppendChild or using an Insert* method.');
        }
        if (!$node->getOwner()->getIsNewRecord()) {
            return $node->moveBelow($this);
        }
        try {
            $minleft = -1;
            $maxright = -1;

            // If the parent has no children insert node as only child
            if (1 == $parent->getRightValue() - $parent->getLeftValue()) {
                $minleft = $parent->getLeftValue() + 1;
                $minright = $parent->getRightValue();
                $cond = '> ' . $parent->getLeftValue();
                $lv = $parent->getLeftValue() + 1;
            }
            // else, if a valid brother is specified
            elseif ((null != $brother) && ($brother->getLeftValue() > $parent->getLeftValue()) && ($brother->getRightValue() < $parent->getRightValue())) {
                $minleft = $brother->getLeftValue();
                $minright = $brother->getRightValue();
                $cond = '>= ' . $brother->getLeftValue();
                $lv = $brother->getLeftValue();
            }
            // else insert node as last child
            else {
                $minleft = $parent->getRightValue();
                $minright = $parent->getRightValue();
                $cond = '>= ' . $parent->getRightValue();
                $lv = $parent->getRightValue();
            }
            $rv = $lv + 1;

            $sql = 'UPDATE %3$s SET %1$s = %1$s + 2 WHERE %1$s %2$s';
            $command = $this->Owner->dbConnection->createCommand(sprintf($sql, $this->_lftCol, $cond, $this->Owner->tableName()));
            $command->execute();
            $command = $this->Owner->dbConnection->createCommand(sprintf($sql, $this->_rgtCol, $cond, $this->Owner->tableName()));
            $command->execute();

            self::updateRegisteredNodeRange(get_class($this->Owner), 2, $minleft, $minright);

            $node->setLeftValue($lv);
            $node->setRightValue($rv);
            $node->setLevelValue($this->getLevelValue() + 1);
            $node->save();

            $transaction->commit();

            return true;
        } catch (Exception $e) {
            Yii::log('Error appending node, transaction aborted. Exception: ' . $e->getMessage(), 'error', 'application.extensions.nestedset.treebehavior');
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * Returns an array with all siblings of the current node. The current node is not included in this array.
     *
     * @return array An array with sibling nodes;
     */
    public function getSiblings()
    {
        Yii::trace(get_class($this) . '.getSiblings()', 'extensions.nestedset.treebehavior.TreeBehavior');

        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t get the siblings of a node that is not yet in the database. Append node to the tree first using AppendChild or an Insert method.');
        }

        $parent = $this->getParentNode();
        $children = $parent->getChildNodes();
        $pk = $this->Owner->primaryKey();

        foreach ($children as $child) {
            if ($this != $child) {
                $res[] = $child;
            }
        }

        return $res;
    }

    /**
     * Returns the next sibling (i.e. the sibling on the right of this node).
     *
     * @return CActiveTreeRecord The next sibling or null when no sibling is found
     */
    public function getNextSibling()
    {
        Yii::trace(get_class($this) . '.getNextSibling()', 'extensions.nestedset.treebehavior.TreeBehavior');

        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t get the siblings of a node that is not yet in the database. Append node to the tree first using AppendChild or an Insert method.');
        }

        $builder = $this->Owner->getCommandBuilder();
        $cstring = $this->_lftCol . ' = ?';
        $criteria = $builder->createCriteria($cstring, [$this->getRightValue() + 1]);
        $criteria->limit = 1;
        $command = $builder->createFindCommand($this->Owner->getTableSchema(), $criteria);

        return $this->Owner->populateRecord($command->queryRow());
    }

    /**
     * Returns the previous sibling (i.e. the sibling on the left of this node).
     *
     * @return CActiveTreeRecord the previous sibling or null when no sibling is found
     */
    public function getPreviousSibling()
    {
        Yii::trace(get_class($this) . '.getPreviousSibling()', 'extensions.nestedset.treebehavior.TreeBehavior');

        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t get the siblings of a node that is not yet in the database. Append node to the tree first using AppendChild or an Insert method.');
        }

        $builder = $this->Owner->getCommandBuilder();
        $cstring = $this->_rgtCol . ' = ?';
        $criteria = $builder->createCriteria($cstring, [$this->getLeftValue() - 1]);
        $criteria->limit = 1;
        $command = $builder->createFindCommand($this->Owner->getTableSchema(), $criteria);

        return $this->Owner->populateRecord($command->queryRow());
    }

    /**
     * Returns the parent node of the current node.
     *
     * @return CActiveTreeRecord The parent node or null when there is no parent found (for example, when this node is the root node)
     */
    public function getParentNode()
    {
        Yii::trace(get_class($this) . '.getParentNode()', 'extensions.nestedset.treebehavior.TreeBehavior');

        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t get the parent of a node that is not yet in the database. Append node to the tree first using AppendChild or an Insert method.');
        }

        $builder = $this->Owner->getCommandBuilder();
        $cstring = $this->_lftCol . ' < ? AND ' . $this->_rgtCol . ' > ? AND ' . $this->_lvlCol . ' = ?';
        $criteria = $builder->createCriteria($cstring, [$this->getLeftValue(), $this->getRightValue(), $this->getLevelValue() - 1]);
        $criteria->limit = 1;
        $command = $builder->createFindCommand($this->Owner->getTableSchema(), $criteria);

        return $this->Owner->populateRecord($command->queryRow());
    }

    /**
     * Insert this node before the given node (as a sibling on the left side).
     *
     * @param CActiveTreeRecord $node The given node
     *
     * @return bool true on success or False on failure
     */
    public function insertBefore($node)
    {
        Yii::trace(get_class($this) . '.insertBefore()', 'extensions.nestedset.treebehavior.TreeBehavior');
        $parent = $node->getParentNode();
        if (null == $parent) {
            return false;
        }

        return $parent->appendChild($this, $node);
    }

    /**
     * Insert this node after the given node (as a sibling on the right side).
     *
     * @param CActiveTreeRecord $node The given node
     *
     * @return bool true on success or False on failure
     */
    public function insertAfter($node)
    {
        Yii::trace(get_class($this) . '.insertAfter()', 'extensions.nestedset.treebehavior.TreeBehavior');
        $next = $node->getNextSibling();
        if (null == $next) {
            return $node->getParentNode()->appendChild($this);
        } else {
            return $next->insertBefore($this);
        }
    }

    /**
     * Function to delete a node from the tree. The object does still exist, and you can insert it again in the tree.
     * In this case, it will get a new ID value.
     * Remember that when you delete the children as well, they are they are gone forever (even when you insert this node again).
     *
     * @param bool $deleteChildren When true, all children will also be deleted. This action CANNOT be undone!!!
     *
     * @return bool True on succes, False on failure. (Check the logs when deletion fails)
     */
    public function deleteNode($deleteChildren = false)
    {
        Yii::trace(get_class($this) . '.deleteNode()', 'extensions.nestedset.treebehavior.TreeBehavior');
        if ($this->getOwner()->getIsNewRecord() || null == $this->getLeftValue() || null == $this->getRightValue() || null == $this->getLevelValue()) {
            throw new TreeException('Node is not attached to the tree.');
        }

        if (0 === $this->getDepth()) {
            throw new TreeException('Cannot delete the root node.');
        }

        if ($deleteChildren) {
            $transaction = $this->Owner->dbConnection->beginTransaction();
            try {
                $width = $this->getRightValue() - $this->getLeftValue() + 1;

                $sql = 'DELETE FROM %1$s WHERE %2$s >= %4$s AND %3$s <= %5$s';
                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->_rgtCol, $this->getLeftValue(), $this->getRightValue());
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                $sql = 'UPDATE %1$s SET %2$s = %2$s - %4$s WHERE %2$s > %3$s';
                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_rgtCol, $this->getRightValue(), $width);
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->getRightValue(), $width);
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();
                $transaction->commit();
                self::updateRegisteredNodeRange(get_class($this->Owner), -$width, $this->getRightValue() + 1, $this->getRightValue() + 1);
            } catch (Exception $e) {
                Yii::log('Error deleting nodes, transaction aborted. Exception: ' . $e->getMessage(), 'error', 'application.extensions.nestedset.treebehavior');
                $transaction->rollBack();

                return false;
            }
        } else {
            $transaction = $this->Owner->dbConnection->beginTransaction();
            try {
                //delete the node
                $sql = 'DELETE FROM %1$s WHERE %2$s = %3$s';
                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->getLeftValue());
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                //update the child nodes
                $sql = 'UPDATE %1$s SET %3$s = %3$s - 1, %2$s = %2$s - 1, %4$s = %4$s - 1 WHERE %2$s BETWEEN %5$s AND %6$s';
                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->_rgtCol, $this->_lvlCol, $this->getLeftValue() + 1, $this->getRightValue() - 1);
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                //update the nodes that the user has open as objects.
                self::updateRegisteredNodeRange(get_class($this->Owner), -1, $this->getLeftValue() + 1, $this->getLeftValue() + 1, $this->getRightValue() - 1, $this->getRightValue() - 1);

                //update the rest of the tree
                $sql = 'UPDATE %1$s SET %2$s = %2$s - 2 WHERE %2$s > %3$s';
                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_rgtCol, $this->getRightValue());
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->getRightValue());
                $command = $this->Owner->dbConnection->createCommand($cstring);
                $command->execute();

                //update the nodes that the user has open as objects.
                self::updateRegisteredNodeRange(get_class($this->Owner), -2, $this->getRightValue() + 1, $this->getRightValue() + 1);

                $transaction->commit();
            } catch (Exception $e) {
                Yii::log('Error deleting single node, transaction aborted. Exception: ' . $e->getMessage(), 'error', 'application.extensions.nestedset.treebehavior');
                $transaction->rollBack();

                return false;
            }
        }
        $this->setLeftValue(null);
        $this->setRightValue(null);
        $this->setIDValue(null);
        $this->setLevelValue(null);
    }

    /**
     * Move a node one level up.
     *
     * @param bool $after When true, the node is places after the parent node (this is the default behaviour).
     *                    When false, the node is places before the parent.
     *
     * @return bool returns true on succes
     *
     * @throws TreeException on any error during moving the node
     */
    public function moveUp($after = true)
    {
        Yii::trace(get_class($this) . '.moveUp()', 'extensions.nestedset.treebehavior.TreeBehavior');
        $parent = $this->getParentNode();
        if (0 == $parent->getLeftValue()) {
            throw new TreeException('Cannot move node up to root level.');
        }

        return $this->moveNode($this->getParentNode(), $after, false);
    }

    /**
     * Move a node below a given node (i.e. appends this node as a child of the given node).
     *
     * @param $node the node in the tree which becomes the new parent node
     * @param bool $lastChild Append the node as the last child of the given parent node
     *
     * @return bool returns true on succes
     *
     * @throws TreeException on any error during moving the node
     */
    public function moveBelow($node, $lastChild = true)
    {
        Yii::trace(get_class($this) . '.moveBelow()', 'extensions.nestedset.treebehavior.TreeBehavior');

        return $this->moveNode($node, $lastChild, true);
    }

    /**
     * Move a node to the left (i.e. exchange it with its left sibling)
     * Returns false when the node is in its most left position and there are no left siblings anymore.
     *
     * @return bool true on success
     */
    public function moveLeft()
    {
        Yii::trace(get_class($this) . '.moveLeft()', 'extensions.nestedset.treebehavior.TreeBehavior');
        $leftNode = $this->getPreviousSibling();
        if (null == $leftNode) {
            return false;
        } else {
            return $this->moveNode($leftNode, false, false);
        }
    }

    /**
     * Move this node to a position before the given node. (i.e. this node becomes the left sibling of the given node).
     *
     * @param $node The given node
     *
     * @return bool true on success
     */
    public function moveBefore($node)
    {
        Yii::trace(get_class($this) . '.moveBefore()', 'extensions.nestedset.treebehavior.TreeBehavior');

        return $this->moveNode($node, false, false);
    }

    /**
     * Move this node to a position after the given node. (i.e. this node becomes the right sibling of the given node).
     *
     * @param $node The given node
     *
     * @return bool true on success
     */
    public function moveAfter($node)
    {
        Yii::trace(get_class($this) . '.moveAfter()', 'extensions.nestedset.treebehavior.TreeBehavior');

        return $this->moveNode($node, true, false);
    }

    /**
     * Move a node to the right (i.e. exchange it with its right sibling)
     * Returns false when the node is in its most right position and there are no right siblings anymore.
     *
     * @return bool true on success
     */
    public function moveRight()
    {
        Yii::trace(get_class($this) . '.moveRight()', 'extensions.nestedset.treebehavior.TreeBehavior');
        $rightNode = $this->getNextSibling();
        if (null == $rightNode) {
            return false;
        } else {
            return $this->moveNode($rightNode, true, false);
        }
    }

    /**
     * Moves the node to a new location relative to the given new sibling.
     *
     * NEVER use this function directly, unless you want to write unreadable code.
     * Otherwise, leave it as it is: an internal function.
     *
     *
     * @param bool $after   Insert the node after the sibling (or before)
     * @param bool $aschild false = as sibling, true = aschild
     */
    private function moveNode($sibling, $after = false, $aschild = false)
    {
        Yii::trace(get_class($this) . '.moveNode()', 'extensions.nestedset.treebehavior.TreeBehavior');
        if ($this->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t move a node that is not yet in the database. Append node to the tree first using AppendChild or an Insert method.');
        }
        if ($sibling->getOwner()->getIsNewRecord()) {
            throw new TreeException('You can\'t move a node using a sibling that is not yet in the database. Append the sibling to the tree first using AppendChild or an Insert method.');
        }

        $aschild = $aschild ? 1 : 0;

        $movetree = $this->getTree();
        $rgt = $this->_rgtCol;
        $lft = $this->_lftCol;

        if (!is_array($movetree) or null == $sibling or 0 == count($movetree)) {
            return false;
        }

        $movenode = reset($movetree);
        // This code is quite difficult to understand (about the same level as the
        // rest of this file ;), the general strategy is to find all the nodes that
        // are affected by the move (excluding the moving nodes), by calculating
        // the range of lft and rgt's those nodes are in.  How this is calculated
        // depends on the direction and whether the node must be inserted before or
        // after the identified sibling.  If you don't get it, don't worry, you can
        // always check out the nice symmetry of the code :)

        // The shift all affected nodes are going to make.  Always the same, equal
        // to sizeof($movetree) * 2
        $diff = $movenode->getRightValue() - $movenode->getLeftValue() + 1;
        $ymove = $sibling->getLevelValue() - $movenode->getLevelValue();
        if (1 == $aschild) {
            ++$ymove;
        }

        if ($after) {
            $aschild *= -1;
            $compare = $sibling->getRightValue();
        } else {
            $compare = $sibling->getLeftValue();
        }

        if ($movenode->getLeftValue() < $compare) {
            // Moving down!
            $lower = $movenode->getRightValue() + 1;
            if ($after) {
                $upper = $sibling->getRightValue();
                $move = $aschild
                         ? $sibling->getRightValue() - $movenode->getRightValue() - 1
                         : $sibling->getRightValue() - $movenode->getRightValue();
            } else { // before
                $upper = $sibling->getLeftValue() - 1;
                $move = $aschild
                         ? $sibling->getLeftValue() - $movenode->getRightValue()
                         : $sibling->getLeftValue() - $movenode->getRightValue() - 1;
            }
            // sibling or child
            $upper += $aschild;
            // direction
            $diff *= -1;
        } else {
            // Going up!
            $upper = $movenode->getLeftValue() - 1;
            if ($after) {
                $lower = $sibling->getRightValue() + 1;
                $move = $aschild
                         ? $movenode->getLeftValue() - $sibling->getRightValue()
                         : $movenode->getLeftValue() - $sibling->getRightValue() - 1;
            } else { // before
                $lower = $sibling->getLeftValue();
                $move = $aschild
                         ? $movenode->getLeftValue() - $sibling->getLeftValue() + 1
                         : $movenode->getLeftValue() - $sibling->getLeftValue();
            }
            // sibling or child
            $lower += $aschild;
            // direction
            $move *= -1;
        }

        if ($lower > $upper) {
            // This (only?) (always?) happens when trying to move a node to the place
            // it already is.  (Using yourself as sibling is already caught.)
            return false;
        }

        $transaction = $this->Owner->dbConnection->beginTransaction();
        try {
            //update the lft/rgt values of the rest of the tree.
            $sql = 'UPDATE %1$s SET %2$s = %2$s + %3$d WHERE %2$s BETWEEN %4$s AND %5$s';
            $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $diff, $lower, $upper);

            $command = $this->Owner->dbConnection->createCommand($cstring);
            $command->execute();

            $cstring = sprintf($sql, $this->Owner->tableName(), $this->_rgtCol, $diff, $lower, $upper);
            $command = $this->Owner->dbConnection->createCommand($cstring);
            $command->execute();

            //move the node + the children
            $ids = [];
            foreach ($movetree as $n) {
                $ids[] = $n->getIDValue();
            }

            $sql = 'UPDATE %1$s SET %2$s = %2$s + %4$s, %3$s = %3$s + %4$s, %5$s = %5$s + %6$s WHERE %7$s IN (%8$s)';
            $cstring = sprintf($sql, $this->Owner->tableName(), $this->_lftCol, $this->_rgtCol, $move, $this->_lvlCol, $ymove, $this->_idCol, join(',', $ids));

            $command = $this->Owner->dbConnection->createCommand($cstring);
            $command->execute();
            $transaction->commit();

            self::updateRegisteredNodeRange(get_class($this->Owner), $diff, $lower, $lower, $upper, $upper);
            self::updateRegisteredNodes(get_class($this->Owner), $ids, $move, $ymove);
            //update the objects
        } catch (Exception $e) {
            Yii::log('Error moving node, transaction aborted. Exception: ' . $e->getMessage(), 'error', 'application.extensions.nestedset.treebehavior');
            $transaction->rollBack();

            return false;
        }

        return true;
    }

    /**
     * Returns the tree in an flat array.
     *
     * @param bool $returnrootnode true = return an array including the root node
     */
    public function getTree($returnrootnode = true)
    {
        Yii::trace(get_class($this) . '.getTree()', 'extensions.nestedset.treebehavior.TreeBehavior');

        $condition = $this->_lftCol . ' >= ? AND ' . $this->_rgtCol . ' <= ?';
        $params = [$this->getLeftValue(), $this->getRightValue()];
        $builder = $this->Owner->getCommandBuilder();
        $criteria = $builder->createCriteria($condition, $params);
        $criteria->order = $this->_lftCol . ' ASC';
        $command = $builder->createFindCommand($this->Owner->getTableSchema(), $criteria);
        $nodes = $this->Owner->populateRecords($command->queryAll());

        if (!$returnrootnode) {
            array_shift($nodes);
        }

        return $nodes;
    }

    /**
     * Returns the enire tree in a nested array
     * Every "node" in this array is an array which has two key/value combinations:
     * <ul>
     *  <li>'node': The actual node object (like this one)</li>
     *  <li>'children': A list of children of the node. Every child is again an array with these to key/value combinations.</li>
     * </ul>.
     *
     * @param $returnrootnode  Whether the rood node should be included in de result
     */
    public function getNestedTree($returnrootnode = true, $keyfield = null)
    {
        if (null == $keyfield) {
            $keyfield = 'id';
        }
        Yii::trace(get_class($this) . '.getNestedTree()', $keyfield);
        // Fetch the flat tree
        $rawtree = $this->getTree(true);

        // Init variables needed for the array conversion
        $tree = [];
        $node = &$tree;
        $depth = 0;
        $position = [];
        $lastitem = '';

        foreach ($rawtree as $rawitem) {
            // If its a deeper item, then make it subitems of the current item
            if ($rawitem->getLevelValue() > $depth) {
                $position[] = &$node; //$lastitem;
                $depth = $rawitem->getLevelValue();
                $node = &$node[$lastitem]['children'];
            }
            // If its less deep item, then return to a level up
            else {
                while ($rawitem->getLevelValue() < $depth) {
                    end($position);
                    $node = &$position[key($position)];
                    array_pop($position);
                    $depth = $node[key($node)]['node']->getLevelValue();
                }
            }

            // Add the item to the final array
            $node[$rawitem->$keyfield]['node'] = $rawitem;
            // save the last items' name
            $lastitem = $rawitem->$keyfield;
        }

        // we don't care about the root node
        if (!$returnrootnode) {
            reset($tree);
            $tree = $tree[key($tree)]['children'];
            //array_shift($tree);
        }

        return $tree;
    }
}
