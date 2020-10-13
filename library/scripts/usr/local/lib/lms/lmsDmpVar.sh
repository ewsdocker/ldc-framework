# *********************************************************************************
# *********************************************************************************
#
#   lmsDmpVar
#
# *****************************************************************************
#
# @author Jay Wheeler.
# @version 0.1.4
# @copyright © 2016, 2017, 2018. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage lmsDmpVar
#
# *****************************************************************************
#
#	Copyright © 2016-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-utilities.
#
#   ewsdocker/ldc-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-utilities.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# *****************************************************************************
#
#			Version 0.0.1 - 06-26-2016.
#					0.0.2 - 09-06-2016.
#					0.1.0 - 01-15-2017.
#					0.1.1 - 02-09-2017.
#					0.1.2 - 08-25-2018.
#                   0.1.3 - 07-22-2019.
#                   0.1.4 - 10-01-2020.
#
# *********************************************************************************
# ***********************************************************************************************************

# ***********************************************************************************************************
#
#	lmsDmpVar
#
#		dump the name table for debug purposes
#
#	attributes:
#		none
#
#	returns:
#		0 = no error
#
# *********************************************************************************
lmsDmpVar()
{
	eval declare -p |
	{
		local -i lineNumber=0

		echo ""
		echo "Variable contents:"

		while IFS= read -r line
		do
    		printf "% 5u : %s\n" $lineNumber "$line"
			let lineNumber+=1
		done
	}
	
}

# ***********************************************************************************************************
#
#	lmsDmpVarStack
#
#		dump the call stack for debug purposes
#
#	parameterss:
#		none
#
#	returns:
#		0 = no error
#		non-zero = error code
#
# *********************************************************************************
lmsDmpVarStack()
{
	local frame=0

	echo ""
	echo "Stack contents:"
	echo "---------------"

	while caller $frame
	do
		((frame++));
	done

	echo "$*"
}

