<?php

namespace App\Http\Controllers;

use App\Models\ParentInfo;
use App\Models\Guardian;
use App\Models\Child;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FamilyController extends Controller
{
    /**
     * Parent (ParentInfo) CRUD Operations
     * Database table: parent_info
     */

    /**
     * Get all parents
     */
    public function getParents(): JsonResponse
    {
        $parents = ParentInfo::with('children')->get();
        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }

    /**
     * Create a new parent
     */
    public function createParent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:parent_info,email',
                'birthday' => 'nullable|date',
            ]);

            $parent = ParentInfo::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Parent created successfully',
                'data' => $parent
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create parent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific parent with their children (loop)
     */
    public function getParent(int $id): JsonResponse
    {
        $parent = ParentInfo::with('children', 'phoneNumbers')->find($id);

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $parent
        ]);
    }

    /**
     * Update a parent
     */
    public function updateParent(Request $request, int $id): JsonResponse
    {
        try {
            $parent = ParentInfo::find($id);

            if (!$parent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent not found'
                ], 404);
            }

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:parent_info,email,' . $id,
                'birthday' => 'nullable|date',
            ]);

            $parent->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Parent updated successfully',
                'data' => $parent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update parent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a parent
     */
    public function deleteParent(int $id): JsonResponse
    {
        try {
            $parent = ParentInfo::find($id);

            if (!$parent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent not found'
                ], 404);
            }

            $parent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Parent deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete parent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardian CRUD Operations
     * Database table: guardians
     */

    /**
     * Get all guardians
     */
    public function getGuardians(): JsonResponse
    {
        $guardians = Guardian::with('children')->get();
        return response()->json([
            'success' => true,
            'data' => $guardians
        ]);
    }

    /**
     * Create a new guardian
     */
    public function createGuardian(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:guardians,email',
                'birthday' => 'nullable|date',
                'relationship' => 'required|string|max:255',
            ]);

            $guardian = Guardian::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Guardian created successfully',
                'data' => $guardian
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create guardian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific guardian with their children (loop)
     */
    public function getGuardian(int $id): JsonResponse
    {
        $guardian = Guardian::with('children', 'phoneNumbers', 'parentInfos')->find($id);

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Guardian not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guardian
        ]);
    }

    /**
     * Update a guardian
     */
    public function updateGuardian(Request $request, int $id): JsonResponse
    {
        try {
            $guardian = Guardian::find($id);

            if (!$guardian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guardian not found'
                ], 404);
            }

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'nullable|email|unique:guardians,email,' . $id,
                'birthday' => 'nullable|date',
                'relationship' => 'sometimes|string|max:255',
            ]);

            $guardian->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Guardian updated successfully',
                'data' => $guardian
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update guardian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a guardian
     */
    public function deleteGuardian(int $id): JsonResponse
    {
        try {
            $guardian = Guardian::find($id);

            if (!$guardian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guardian not found'
                ], 404);
            }

            $guardian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guardian deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete guardian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Child CRUD Operations with Loop Support
     * Database table: children
     */

    /**
     * Get all children (loop - can have multiple per parent/guardian)
     */
    public function getChildren(): JsonResponse
    {
        $children = Child::with('parentInfo', 'guardian')->get();
        return response()->json([
            'success' => true,
            'data' => $children
        ]);
    }

    /**
     * Create a new child (loop - can add multiple children to a parent/guardian)
     */
    public function createChild(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'parent_info_id' => 'nullable|exists:parent_info,id',
                'guardian_id' => 'nullable|exists:guardians,id',
                'name' => 'required|string|max:255',
                'birthday' => 'required|date',
                'playtime_duration' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);

            // At least one of parent or guardian must be provided
            if (!$validated['parent_info_id'] && !$validated['guardian_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either parent_info_id or guardian_id is required'
                ], 422);
            }

            $child = Child::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Child created successfully',
                'data' => $child
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create child: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific child
     */
    public function getChild(int $id): JsonResponse
    {
        $child = Child::with('parentInfo', 'guardian')->find($id);

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Child not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $child
        ]);
    }

    /**
     * Update a child
     */
    public function updateChild(Request $request, int $id): JsonResponse
    {
        try {
            $child = Child::find($id);

            if (!$child) {
                return response()->json([
                    'success' => false,
                    'message' => 'Child not found'
                ], 404);
            }

            $validated = $request->validate([
                'parent_info_id' => 'nullable|exists:parent_info,id',
                'guardian_id' => 'nullable|exists:guardians,id',
                'name' => 'sometimes|string|max:255',
                'birthday' => 'sometimes|date',
                'playtime_duration' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
            ]);

            $child->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Child updated successfully',
                'data' => $child
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update child: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a child
     */
    public function deleteChild(int $id): JsonResponse
    {
        try {
            $child = Child::find($id);

            if (!$child) {
                return response()->json([
                    'success' => false,
                    'message' => 'Child not found'
                ], 404);
            }

            $child->delete();

            return response()->json([
                'success' => true,
                'message' => 'Child deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete child: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all children for a specific parent (loop)
     */
    public function getParentChildren(int $parentId): JsonResponse
    {
        $parent = ParentInfo::find($parentId);

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent not found'
            ], 404);
        }

        $children = $parent->children;

        return response()->json([
            'success' => true,
            'data' => $children
        ]);
    }

    /**
     * Get all children for a specific guardian (loop)
     */
    public function getGuardianChildren(int $guardianId): JsonResponse
    {
        $guardian = Guardian::find($guardianId);

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Guardian not found'
            ], 404);
        }

        $children = $guardian->children;

        return response()->json([
            'success' => true,
            'data' => $children
        ]);
    }

    /**
     * Link a parent and guardian together
     */
    public function linkParentGuardian(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'parent_info_id' => 'required|exists:parent_info,id',
                'guardian_id' => 'required|exists:guardians,id',
            ]);

            $parent = ParentInfo::find($validated['parent_info_id']);
            $guardian = Guardian::find($validated['guardian_id']);

            // Check if already linked
            if ($parent->guardians()->where('guardian_id', $validated['guardian_id'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent and guardian are already linked'
                ], 422);
            }

            $parent->guardians()->attach($validated['guardian_id']);

            return response()->json([
                'success' => true,
                'message' => 'Parent and guardian linked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to link parent and guardian: ' . $e->getMessage()
            ], 500);
        }
    }
}
