/**
 * Recursive types in TypeScript allow you to define a type that refers to
 * itself within its own definition. This can be useful when working with data
 * structures that have a nested or hierarchical nature, such as trees or linked
 * lists.
 *
 * For example, suppose you want to define a type for a binary tree node in
 * TypeScript. The type would need to have a value property, as well as left
 * and right child nodes that are themselves binary tree nodes. Here's how you
 * might define such a type using recursion:
 *
 * type TreeNode = {
 *   value: number;
 *   left?: TreeNode;
 *   right?: TreeNode;
 * }
 *
 * In this definition, the TreeNode type refers to itself within its own
 * definition. This allows you to create binary trees of arbitrary depth, with
 * each node potentially having its own left and right child nodes.
 *
 * Recursive types can also be used to define more complex data structures,
 * such as linked lists or graphs. However, it's important to be mindful of the
 * potential for infinite recursion if the type definition is not properly
 * constrained.
**/

// Create a type for LinkedListNode
interface LinkedListNode = {
  // it has both a value and a head.
}

class LinkedList {

  add(value) {
    const newNode: LinkedListNode = { value };
    if (!this.head) {
      this.head = newNode;
    } else {
      let currentNode = this.head;
      while (currentNode.next) {
        currentNode = currentNode.next;
      }
      currentNode.next = newNode;
    }
  }

  remove(value) {
    if (!this.head) {
      return;
    }
    if (this.head.value === value) {
      this.head = this.head.next;
      return;
    }
    let currentNode = this.head;
    while (currentNode.next) {
      if (currentNode.next.value === value) {
        currentNode.next = currentNode.next.next;
        return;
      }
      currentNode = currentNode.next;
    }
  }
}

